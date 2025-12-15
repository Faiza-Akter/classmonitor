<?php

namespace App\Http\Controllers;

use App\Models\AttendanceSession;
use App\Models\AttendanceRecord;
use Illuminate\Http\Request;

class TeacherDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

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

        // keep as placeholders until quiz system is built
        $avgScore = null;
        $pendingReviews = 0;

        return view('dashboard.teacher', compact(
            'activeAttendance',
            'todaySessions',
            'todayCheckins',
            'liveCheckins',
            'avgScore',
            'pendingReviews'
        ));
    }
}
