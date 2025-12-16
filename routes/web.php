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

    if (!$user)
        return redirect()->route('login');

    return $user->role === 'teacher'
        ? redirect()->route('teacher.dashboard')
        : redirect()->route('student.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {

    // Teacher Dashboard (controller so we can pass stats)
    Route::get('/teacher/dashboard', [TeacherDashboardController::class, 'index'])
        ->middleware('teacher')
        ->name('teacher.dashboard');

    // Student Dashboard (still view for now)
    Route::view('/student/dashboard', 'dashboard.student')
        ->middleware('student')
        ->name('student.dashboard');

    // Attendance (Teacher)
    Route::get('/attendance', [AttendanceController::class, 'index'])
        ->middleware('teacher')
        ->name('attendance.index');

    Route::post('/attendance/sessions', [AttendanceController::class, 'create'])
        ->middleware('teacher')
        ->name('attendance.sessions.create');

    Route::post('/attendance/sessions/{session}/end', [AttendanceController::class, 'end'])
        ->middleware('teacher')
        ->name('attendance.sessions.end');

    Route::get('/attendance/sessions/{session}/live', [AttendanceController::class, 'live'])
        ->middleware('teacher')
        ->name('attendance.sessions.live');

    // Attendance (Student)
    Route::get('/attendance/join', [AttendanceController::class, 'joinForm'])
        ->middleware('student')
        ->name('attendance.join.form');

    Route::post('/attendance/join', [AttendanceController::class, 'join'])
        ->middleware('student')
        ->name('attendance.join');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Student Attendance History
    Route::get('/student/attendance', [AttendanceController::class, 'studentHistory'])
        ->middleware('student')
        ->name('student.attendance.history');

    Route::get('/attendance/export/csv', [AttendanceController::class, 'exportCsv'])
        ->middleware('teacher')
        ->name('attendance.export.csv');

    // Teacher Quiz
    Route::middleware('teacher')->group(function () {
        Route::get('/quizzes', [QuizController::class, 'teacherIndex'])->name('quizzes.index');
        Route::get('/quizzes/create', [QuizController::class, 'teacherCreate'])->name('quizzes.create');
        Route::post('/quizzes', [QuizController::class, 'teacherStore'])->name('quizzes.store');

        Route::get('/quizzes/{quiz}/manage', [QuizController::class, 'teacherManage'])->name('quizzes.manage');
        Route::post('/quizzes/{quiz}/questions', [QuizController::class, 'teacherAddQuestion'])->name('quizzes.questions.add');

        Route::post('/quizzes/{quiz}/start', [QuizController::class, 'teacherStart'])->name('quizzes.start');
        Route::post('/quizzes/{quiz}/stop', [QuizController::class, 'teacherStop'])->name('quizzes.stop');
    });

    // Student Quiz
    Route::middleware('student')->group(function () {
        Route::get('/quizzes/{quiz}/play', [QuizController::class, 'studentPlay'])->name('quizzes.play');
        Route::post('/quizzes/{quiz}/submit', [QuizController::class, 'studentSubmit'])->name('quizzes.submit');
        Route::get('/quizzes/{quiz}/result', [QuizController::class, 'studentResult'])->name('quizzes.result');
    });


});

require __DIR__ . '/auth.php';
