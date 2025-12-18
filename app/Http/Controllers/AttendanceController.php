<?php

namespace App\Http\Controllers;

use App\Models\AttendanceSession;
use App\Models\AttendanceRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AttendanceController extends Controller
{
    // Teacher page
    public function index(Request $request)
    {
        $teacher = $request->user();

        $activeSession = AttendanceSession::where('teacher_id', $teacher->id)
            ->active()
            ->latest('id')
            ->first();

        $recentSessions = AttendanceSession::where('teacher_id', $teacher->id)
            ->latest('id')
            ->take(10)
            ->get();

        return view('attendance.index', compact('activeSession', 'recentSessions'));
    }

    // eacher session details page (QR + full record list)
    public function show(Request $request, AttendanceSession $session)
    {
        $teacher = $request->user();
        abort_unless($session->teacher_id === $teacher->id, 403);

        $session->load('teacher');

        $records = $session->records()
            ->with('student:id,name,email')
            ->latest('marked_at')
            ->paginate(25);

        $count = $session->records()->count();

        return view('attendance.show', [
            'session' => $session,
            'records' => $records,
            'count'   => $count,
        ]);
    }

    // Teacher creates a new session (anti-proxy: expires in N minutes)
    public function create(Request $request)
    {
        $request->validate([
            'expires_minutes' => ['required', 'integer', 'min:1', 'max:120'],
        ]);

        $teacher = $request->user();

        // end previous active session (optional but clean)
        AttendanceSession::where('teacher_id', $teacher->id)
            ->active()
            ->update(['ended_at' => now()]);

        $code = strtoupper(Str::random(6));

        // ensure unique (rare collision)
        while (AttendanceSession::where('session_code', $code)->exists()) {
            $code = strtoupper(Str::random(6));
        }

        $session = AttendanceSession::create([
            'teacher_id'   => $teacher->id,
            'session_code' => $code,
            'starts_at'    => now(),
            'expires_at'   => now()->addMinutes((int) $request->expires_minutes),
        ]);

        //Better UX: go directly to session page (QR + live list)
        return redirect()
            ->route('attendance.sessions.show', $session)
            ->with('success', 'Attendance session created.');
    }

    public function end(Request $request, AttendanceSession $session)
    {
        $teacher = $request->user();
        abort_unless($session->teacher_id === $teacher->id, 403);

        $session->update(['ended_at' => now()]);

        return back()->with('success', 'Session ended.');
    }

    // Student join by code
    public function joinForm()
    {
        return view('attendance.join');
    }

    public function join(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'max:10'],
        ]);

        $student = $request->user();
        $code = strtoupper(trim($request->code));

        $session = AttendanceSession::where('session_code', $code)->first();

        if (!$session) {
            return back()->withErrors(['code' => 'Invalid code.'])->withInput();
        }

        if ($session->ended_at) {
            return back()->withErrors(['code' => 'This session has ended.'])->withInput();
        }

        if ($session->expires_at && $session->expires_at->isPast()) {
            return back()->withErrors(['code' => 'This session has expired.'])->withInput();
        }

        AttendanceRecord::firstOrCreate(
            [
                'attendance_session_id' => $session->id,
                'student_id' => $student->id,
            ],
            [
                'marked_at' => now(),
            ]
        );

        return redirect()->route('student.dashboard')->with('success', 'Attendance marked!');
    }

    // Teacher live endpoint (polling)
    public function live(Request $request, AttendanceSession $session)
    {
        $teacher = $request->user();
        abort_unless($session->teacher_id === $teacher->id, 403);

        $count = $session->records()->count();

        $latest = $session->records()
            ->with('student:id,name,email')
            ->latest('marked_at')
            ->take(8)
            ->get()
            ->map(fn($r) => [
                'name' => $r->student?->name ?? 'Student',
                'email' => $r->student?->email ?? '',
                // use app timezone
                'time' => $r->marked_at ? $r->marked_at->timezone(config('app.timezone'))->format('h:i A') : '',
            ]);

        return response()->json([
            'count' => $count,
            'latest' => $latest,
        ]);
    }

    /**
     * Student Attendance History
     * URL: /student/attendance
     */
    public function studentHistory(Request $request)
    {
        $student = $request->user();

        $rows = AttendanceRecord::query()
            ->where('student_id', $student->id)
            ->with([
                'session:id,teacher_id,session_code,starts_at,expires_at,ended_at,created_at',
                'session.teacher:id,name'
            ])
            ->latest('marked_at')
            ->paginate(10);

        return view('attendance.student_history', compact('rows'));
    }

    /**
     * Teacher Export Attendance Sessions CSV
     * URL: /attendance/export/csv
     */
    public function exportCsv(Request $request)
    {
        $teacher = $request->user();

        $sessions = AttendanceSession::query()
            ->where('teacher_id', $teacher->id)
            ->withCount('records')
            ->latest('id')
            ->get();

        // Use Bangladesh time for filename too
        $filename = 'attendance_sessions_' . now('Asia/Dhaka')->format('Y_m_d_His') . '.csv';

        return response()->streamDownload(function () use ($sessions) {
            $out = fopen('php://output', 'w');

            //Excel-friendly UTF-8 BOM
            fwrite($out, "\xEF\xBB\xBF");

            // CSV Header
            fputcsv($out, [
                'Session Code',
                'Starts At (BDT)',
                'Expires At (BDT)',
                'Ended At (BDT)',
                'Total Check-ins',
            ]);

            foreach ($sessions as $s) {
                $starts = optional($s->starts_at)?->timezone('Asia/Dhaka')->format('Y-m-d H:i');
                $expires = optional($s->expires_at)?->timezone('Asia/Dhaka')->format('Y-m-d H:i');
                $ended = $s->ended_at
                    ? $s->ended_at->timezone('Asia/Dhaka')->format('Y-m-d H:i')
                    : 'Active';

                fputcsv($out, [
                    $s->session_code,
                    $starts ?? '',
                    $expires ?? '',
                    $ended,
                    (int) ($s->records_count ?? 0),
                ]);
            }

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Cache-Control' => 'no-store, no-cache',
        ]);
    }

}
