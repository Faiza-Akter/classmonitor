<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Dashboard Redirect (Role Based)
|--------------------------------------------------------------------------
| This route decides where to send the user after login/register
*/

Route::get('/dashboard', function () {
    $user = request()->user();

    if (!$user) {
        return redirect()->route('login');
    }

    return $user->role === 'teacher'
        ? redirect()->route('teacher.dashboard')
        : redirect()->route('student.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    // Teacher Dashboard (protected)
    Route::view('/teacher/dashboard', 'dashboard.teacher')
        ->middleware('teacher')
        ->name('teacher.dashboard');

    // Student Dashboard (protected)
    Route::view('/student/dashboard', 'dashboard.student')
        ->middleware('student')
        ->name('student.dashboard');

    // Profile (shared)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Auth Routes (Login / Register / Reset / Verify)
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';
