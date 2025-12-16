<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeacherDashboardController;
use App\Http\Controllers\AttendanceController;
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

});

require __DIR__ . '/auth.php';
