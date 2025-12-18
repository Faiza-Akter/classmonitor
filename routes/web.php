<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeacherDashboardController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentDashboardController;
use App\Models\QuizAttempt;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = request()->user();

    if (!$user)
        return redirect()->route('login');

    return $user->role === 'teacher'
        ? redirect()->route('teacher.dashboard')
        : redirect()->route('student.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {

    // Teacher Dashboard
    Route::get('/teacher/dashboard', [TeacherDashboardController::class, 'index'])
        ->middleware('teacher')
        ->name('teacher.dashboard');

    // Student Dashboard
    Route::get('/student/dashboard', [StudentDashboardController::class, 'index'])
        ->middleware('student')
        ->name('student.dashboard');


    // Profile (shared)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // =========================
    // Attendance
    // =========================

    // Teacher Attendance
    Route::middleware('teacher')->group(function () {
        Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');

        Route::post('/attendance/sessions', [AttendanceController::class, 'create'])->name('attendance.sessions.create');
        Route::post('/attendance/sessions/{session}/end', [AttendanceController::class, 'end'])->name('attendance.sessions.end');
        Route::get('/attendance/sessions/{session}/live', [AttendanceController::class, 'live'])->name('attendance.sessions.live');

        //session details + QR page
        Route::get('/attendance/sessions/{session}', [AttendanceController::class, 'show'])
            ->name('attendance.sessions.show');

        Route::get('/attendance/export/csv', [AttendanceController::class, 'exportCsv'])->name('attendance.export.csv');
    });

    // Student Attendance
    Route::middleware('student')->group(function () {
        Route::get('/attendance/join', [AttendanceController::class, 'joinForm'])->name('attendance.join.form');
        Route::post('/attendance/join', [AttendanceController::class, 'join'])->name('attendance.join');

        Route::get('/student/attendance', [AttendanceController::class, 'studentHistory'])
            ->name('student.attendance.history');
        Route::get('/student/quizzes', [QuizController::class, 'studentHistory'])
            ->name('student.quizzes.history');

    });

    // =========================
    // Quizzes
    // =========================

    // Teacher Quizzes
    Route::middleware('teacher')->group(function () {
        Route::get('/quizzes', [QuizController::class, 'teacherIndex'])->name('quizzes.index');
        Route::get('/quizzes/create', [QuizController::class, 'teacherCreate'])->name('quizzes.create');
        Route::post('/quizzes', [QuizController::class, 'teacherStore'])->name('quizzes.store');

        Route::get('/quizzes/{quiz}/manage', [QuizController::class, 'teacherManage'])->name('quizzes.manage');

        Route::post('/quizzes/{quiz}/questions', [QuizController::class, 'teacherAddQuestion'])
            ->name('quizzes.questions.store');

        Route::get('/quizzes/{quiz}/questions/{question}/edit', [QuizController::class, 'teacherEditQuestion'])
            ->name('quizzes.questions.edit');

        Route::patch('/quizzes/{quiz}/questions/{question}', [QuizController::class, 'teacherUpdateQuestion'])
            ->name('quizzes.questions.update');

        //delete question
        Route::delete('/quizzes/{quiz}/questions/{question}', [QuizController::class, 'teacherDeleteQuestion'])
            ->name('quizzes.questions.destroy');

        Route::post('/quizzes/{quiz}/start', [QuizController::class, 'teacherStart'])->name('quizzes.start');
        Route::post('/quizzes/{quiz}/stop', [QuizController::class, 'teacherStop'])->name('quizzes.stop');

        //manual grading pages (for short answers)
        Route::get('/quizzes/{quiz}/grading', [QuizController::class, 'teacherGradingIndex'])
            ->name('quizzes.grading.index');

        Route::get('/quizzes/{quiz}/grading/{attempt}', [QuizController::class, 'teacherGradingShow'])
            ->name('quizzes.grading.show');

        Route::patch('/quizzes/{quiz}/grading/{attempt}', [QuizController::class, 'teacherGradeAttempt'])
            ->name('quizzes.grading.update');

        // Leaderboard (you already planned this)
        Route::get('/quizzes/{quiz}/leaderboard', [QuizController::class, 'teacherLeaderboard'])
            ->name('quizzes.leaderboard');
    });

    // Student Quizzes
    Route::middleware('student')->group(function () {
        Route::get('/quizzes/{quiz}/play', [QuizController::class, 'studentPlay'])->name('quizzes.play');
        Route::post('/quizzes/{quiz}/submit', [QuizController::class, 'studentSubmit'])->name('quizzes.submit');
        Route::get('/quizzes/{quiz}/result', [QuizController::class, 'studentResult'])->name('quizzes.result');
        Route::get('/student/quizzes', [QuizController::class, 'studentHistory'])
            ->name('student.quizzes.history');

        Route::get('/student/quizzes/{attempt}', [QuizController::class, 'studentAttemptShow'])
            ->name('student.quizzes.show');

    });

});

require __DIR__ . '/auth.php';
