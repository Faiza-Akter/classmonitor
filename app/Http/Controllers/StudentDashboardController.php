<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceRecord;
use App\Models\Quiz;
use App\Models\QuizAttempt;

class StudentDashboardController extends Controller
{
    public function index(Request $request)
    {
        $student = $request->user();

        $attendanceCount = AttendanceRecord::where('student_id', $student->id)->count();

        $quizAttemptsCount = QuizAttempt::where('student_id', $student->id)
            ->whereNotNull('submitted_at')
            ->count();

        $lastAttempt = QuizAttempt::where('student_id', $student->id)
            ->whereNotNull('submitted_at')
            ->with(['quiz.questions:id,quiz_id,points', 'quiz.teacher:id,name'])
            ->latest('submitted_at')
            ->first();

        $lastQuiz = null;
        if ($lastAttempt && $lastAttempt->quiz) {
            $maxScore = (int) $lastAttempt->quiz->questions->sum('points');
            $lastQuiz = [
                'title' => $lastAttempt->quiz->title,
                'teacher' => $lastAttempt->quiz->teacher?->name ?? 'Teacher',
                'score' => (int) $lastAttempt->score,
                'max' => $maxScore,
                'submitted_at' => $lastAttempt->submitted_at,
                'attempt_id' => $lastAttempt->id,
            ];
        }

        $activeQuizzes = Quiz::query()
            ->where('status', 'active')
            ->with('teacher:id,name')
            ->latest('id')
            ->take(6)
            ->get();

        return view('dashboard.student', compact(
            'attendanceCount',
            'quizAttemptsCount',
            'lastQuiz',
            'activeQuizzes'
        ));
    }
}
