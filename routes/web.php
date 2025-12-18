<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeacherDashboardController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = request()->user();

    if (!$user) {
        return redirect()->route('login');
    }

    return $user->role === 'teacher'
        ? redirect()->route('teacher.dashboard')
        : redirect()->route('student.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {

    // =========================
    // Dashboards
    // =========================

    Route::get('/teacher/dashboard', [TeacherDashboardController::class, 'index'])
        ->middleware('teacher')
        ->name('teacher.dashboard');

    Route::view('/student/dashboard', 'dashboard.student')
        ->middleware('student')
        ->name('student.dashboard');

    // =========================
    // Profile (shared)
    // =========================

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

        Route::get('/attendance/export/csv', [AttendanceController::class, 'exportCsv'])->name('attendance.export.csv');
    });

    // Student Attendance
    Route::middleware('student')->group(function () {
        Route::get('/attendance/join', [AttendanceController::class, 'joinForm'])->name('attendance.join.form');
        Route::post('/attendance/join', [AttendanceController::class, 'join'])->name('attendance.join');

        Route::get('/student/attendance', [AttendanceController::class, 'studentHistory'])
            ->name('student.attendance.history');
    });

    // =========================
    // Quizzes
    // =========================

    // Teacher Quizzes (INCLUDING edit/update question routes )
    Route::middleware('teacher')->group(function () {
        Route::get('/quizzes', [QuizController::class, 'teacherIndex'])->name('quizzes.index');
        Route::get('/quizzes/create', [QuizController::class, 'teacherCreate'])->name('quizzes.create');
        Route::post('/quizzes', [QuizController::class, 'teacherStore'])->name('quizzes.store');

        Route::get('/quizzes/{quiz}/manage', [QuizController::class, 'teacherManage'])->name('quizzes.manage');

        Route::post('/quizzes/{quiz}/questions', [QuizController::class, 'teacherAddQuestion'])
            ->name('quizzes.questions.store');

        // FIX: Edit / Update question routes must be inside teacher middleware
        Route::get('/quizzes/{quiz}/questions/{question}/edit', [QuizController::class, 'teacherEditQuestion'])
            ->name('quizzes.questions.edit');

        Route::patch('/quizzes/{quiz}/questions/{question}', [QuizController::class, 'teacherUpdateQuestion'])
            ->name('quizzes.questions.update');

        Route::post('/quizzes/{quiz}/start', [QuizController::class, 'teacherStart'])->name('quizzes.start');
        Route::post('/quizzes/{quiz}/stop', [QuizController::class, 'teacherStop'])->name('quizzes.stop');
    });

    // Student Quizzes
    Route::middleware('student')->group(function () {
        Route::get('/quizzes/{quiz}/play', [QuizController::class, 'studentPlay'])->name('quizzes.play');
        Route::post('/quizzes/{quiz}/submit', [QuizController::class, 'studentSubmit'])->name('quizzes.submit');
        Route::get('/quizzes/{quiz}/result', [QuizController::class, 'studentResult'])->name('quizzes.result');
    });

});

require __DIR__ . '/auth.php';
