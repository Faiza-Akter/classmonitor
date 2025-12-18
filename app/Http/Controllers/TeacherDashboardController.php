<?php

namespace App\Http\Controllers;

use App\Models\AttendanceSession;
use App\Models\AttendanceRecord;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizAnswer;
use Illuminate\Http\Request;

class TeacherDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // -----------------------------
        // Attendance (existing)
        // -----------------------------
        $activeAttendance = AttendanceSession::where('teacher_id', $user->id)
            ->active()
            ->latest('id')
            ->first();

        $todaySessions = AttendanceSession::where('teacher_id', $user->id)
            ->whereDate('created_at', now()->toDateString())
            ->count();

        $todayCheckins = AttendanceRecord::whereHas('session', function ($q) use ($user) {
                $q->where('teacher_id', $user->id);
            })
            ->whereDate('marked_at', now()->toDateString())
            ->count();

        $liveCheckins = $activeAttendance
            ? $activeAttendance->records()->count()
            : 0;

        // -----------------------------
        // Quizzes (REAL values)
        // -----------------------------

        // Active quiz (to show in dashboard + use for Results button)
        $activeQuiz = Quiz::query()
            ->where('teacher_id', $user->id)
            ->where('status', 'active')
            ->latest('id')
            ->first();

        // Avg quiz score across all submitted attempts of this teacher's quizzes
        $avgScore = QuizAttempt::query()
            ->whereHas('quiz', function ($q) use ($user) {
                $q->where('teacher_id', $user->id);
            })
            ->whereNotNull('submitted_at')
            ->avg('score');

        $avgScore = $avgScore !== null ? round((float) $avgScore, 1) : null;

        // Pending reviews = short answers not graded yet (is_correct null)
        $pendingReviews = QuizAnswer::query()
            ->whereNull('is_correct')
            ->whereHas('question', function ($q) {
                $q->where('type', 'short');
            })
            ->whereHas('attempt.quiz', function ($q) use ($user) {
                $q->where('teacher_id', $user->id);
            })
            ->count();

        return view('dashboard.teacher', compact(
            'activeAttendance',
            'todaySessions',
            'todayCheckins',
            'liveCheckins',
            'avgScore',
            'pendingReviews',
            'activeQuiz'
        ));
    }
}
