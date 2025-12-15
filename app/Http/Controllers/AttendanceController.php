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

        AttendanceSession::create([
            'teacher_id' => $teacher->id,
            'session_code' => $code,
            'starts_at' => now(),
            'expires_at' => now()->addMinutes((int) $request->expires_minutes),
        ]);

        return redirect()->route('attendance.index')->with('success', 'Attendance session created.');
    }

    public function end(Request $request, AttendanceSession $session)
    {
        $teacher = $request->user();

        abort_unless($session->teacher_id === $teacher->id, 403);

        $session->update(['ended_at' => now()]);

        return redirect()->route('attendance.index')->with('success', 'Session ended.');
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
                'time' => $r->marked_at?->format('h:i A') ?? '',
            ]);

        return response()->json([
            'count' => $count,
            'latest' => $latest,
        ]);
    }
}
