<?php

namespace App\Http\Controllers;

use App\Models\AttendanceSession;
use App\Models\AttendanceRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AttendanceController extends Controller
{
    /**
     * Teacher page: attendance sessions index
     */
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

    /**
     * Teacher creates a new session (anti-proxy: expires in N minutes)
     */
    public function create(Request $request)
    {
        $request->validate([
            'expires_minutes' => ['required', 'integer', 'min:1', 'max:120'],
        ]);

        $teacher = $request->user();

        // End previous active sessions (clean)
        AttendanceSession::where('teacher_id', $teacher->id)
            ->active()
            ->update(['ended_at' => now()]);

        // Generate unique session code
        $code = strtoupper(Str::random(6));
        while (AttendanceSession::where('session_code', $code)->exists()) {
            $code = strtoupper(Str::random(6));
        }

        AttendanceSession::create([
            'teacher_id'    => $teacher->id,
            'session_code'  => $code,
            'starts_at'     => now(),
            'expires_at'    => now()->addMinutes((int) $request->expires_minutes),
            'ended_at'      => null,
        ]);

        return redirect()
            ->route('attendance.index')
            ->with('success', 'Attendance session created.');
    }

    /**
     * Teacher ends a session
     */
    public function end(Request $request, AttendanceSession $session)
    {
        $teacher = $request->user();

        abort_unless($session->teacher_id === $teacher->id, 403);

        $session->update(['ended_at' => now()]);

        return redirect()
            ->route('attendance.index')
            ->with('success', 'Session ended.');
    }

    /**
     * Student join form
     */
    public function joinForm()
    {
        return view('attendance.join');
    }

    /**
     * Student join by code and mark attendance
     */
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

        return redirect()
            ->route('student.dashboard')
            ->with('success', 'Attendance marked!');
    }

    /**
     * Teacher live endpoint (polling)
     */
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
            ->map(fn ($r) => [
                'name'  => $r->student?->name ?? 'Student',
                'email' => $r->student?->email ?? '',
                'time'  => $r->marked_at?->format('h:i A') ?? '',
            ]);

        return response()->json([
            'count'  => $count,
            'latest' => $latest,
        ]);
    }

    /**
     * ✅ Student Attendance History
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
     * ✅ Teacher Export Attendance Sessions CSV
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

        $filename = 'attendance_sessions_' . now()->format('Y_m_d_His') . '.csv';

        return response()->streamDownload(function () use ($sessions) {
            $out = fopen('php://output', 'w');

            // CSV Header
            fputcsv($out, [
                'Session Code',
                'Starts At',
                'Expires At',
                'Ended At',
                'Total Check-ins',
            ]);

            foreach ($sessions as $s) {
                fputcsv($out, [
                    $s->session_code,
                    optional($s->starts_at)->format('Y-m-d h:i A'),
                    optional($s->expires_at)->format('Y-m-d h:i A'),
                    $s->ended_at ? $s->ended_at->format('Y-m-d h:i A') : 'Active/Running',
                    $s->records_count ?? 0,
                ]);
            }

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
