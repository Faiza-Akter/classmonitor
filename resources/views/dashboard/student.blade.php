@extends('layouts.app')

@section('content')
@php
  $cmBlue='#2463EB'; $cmGreen='#8BDE63'; $cmYellow='#EDB70A';
  $user = auth()->user();
@endphp

<div class="relative min-h-[calc(100vh-88px)] overflow-hidden bg-white text-slate-900">
  <div class="absolute inset-0 -z-10"
       style="background: radial-gradient(1200px 600px at 10% 10%, rgba(36,99,235,.14), transparent 60%),
                      radial-gradient(900px 520px at 90% 20%, rgba(237,183,10,.12), transparent 55%),
                      radial-gradient(1000px 700px at 70% 95%, rgba(139,222,99,.12), transparent 60%),
                      linear-gradient(180deg, #ffffff, #f8fafc);">
  </div>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">

    {{-- Header --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between mb-6 lg:mb-8">
      <div>
        <p class="text-sm font-semibold text-slate-600">Student Dashboard</p>
        <h1 class="mt-1 text-2xl sm:text-3xl font-extrabold text-slate-900">
          Welcome, <span style="color:{{ $cmBlue }};">{{ $user->name }}</span>
        </h1>
        <p class="mt-1 text-sm text-slate-600">Join attendance, play quizzes, and track your progress.</p>
      </div>

      <div class="flex flex-wrap gap-2">
        <a href="{{ route('attendance.join.form') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl font-semibold text-white shadow-sm hover:shadow-md transition"
           style="background:{{ $cmGreen }}; color:#0B1B0F;">
          Join Attendance
        </a>

        <a href="{{ route('student.attendance.history') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl font-semibold border border-slate-200 bg-white shadow-sm hover:shadow-md transition">
          Attendance History
        </a>

        <a href="{{ route('student.quizzes.history') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl font-semibold text-white shadow-sm hover:shadow-md transition"
           style="background:{{ $cmBlue }};">
          Quiz History
        </a>
      </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-5 mb-6 lg:mb-8">
      <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5">
        <p class="text-sm font-semibold text-slate-600">Attendance Marked</p>
        <p class="mt-1 text-3xl font-extrabold">{{ $attendanceCount ?? 0 }}</p>
        <p class="mt-2 text-xs text-slate-500">Total check-ins</p>
      </div>

      <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5">
        <p class="text-sm font-semibold text-slate-600">Quizzes Attempted</p>
        <p class="mt-1 text-3xl font-extrabold">{{ $quizAttemptsCount ?? 0 }}</p>
        <p class="mt-2 text-xs text-slate-500">Submitted attempts</p>
      </div>

      <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5">
        <p class="text-sm font-semibold text-slate-600">Last Quiz Score</p>
        <p class="mt-1 text-3xl font-extrabold">
          {{ $lastQuiz ? $lastQuiz['score'] : '—' }}
          @if($lastQuiz)
            <span class="text-base text-slate-500 font-bold">/ {{ $lastQuiz['max'] }}</span>
          @endif
        </p>
        <p class="mt-2 text-xs text-slate-500">
          {{ $lastQuiz ? $lastQuiz['title'] : 'No attempts yet' }}
        </p>
      </div>

      <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5">
        <p class="text-sm font-semibold text-slate-600">Quick</p>
        <p class="mt-1 text-base font-extrabold text-slate-900">Profile</p>
        <p class="mt-2 text-xs text-slate-500">Update your info</p>
        <div class="mt-4">
          <a href="{{ route('profile.edit') }}"
             class="inline-flex items-center px-4 py-2 rounded-xl font-semibold text-white shadow-sm hover:shadow-md transition"
             style="background:{{ $cmYellow }}; color:#3a2b00;">
            Open Profile
          </a>
        </div>
      </div>
    </div>

    {{-- Active Quizzes --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5 sm:p-6">
      <div class="flex items-start justify-between gap-3">
        <div>
          <h2 class="text-lg font-extrabold text-slate-900">Available Quizzes</h2>
          <p class="text-sm text-slate-600">Only active quizzes show here.</p>
        </div>
        <div class="w-11 h-11 rounded-2xl grid place-items-center" style="background:rgba(36,99,235,.14);">
          <span class="w-2.5 h-2.5 rounded-full" style="background:{{ $cmBlue }};"></span>
        </div>
      </div>

      @if(($activeQuizzes ?? collect())->count() === 0)
        <div class="mt-4 rounded-2xl border border-slate-200 bg-slate-50 p-5 text-sm text-slate-600">
          No active quizzes right now.
        </div>
      @else
        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3">
          @foreach($activeQuizzes as $q)
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
              <p class="text-sm font-bold text-slate-900">{{ $q->title }}</p>
              <p class="mt-1 text-xs text-slate-600">Teacher: {{ $q->teacher?->name ?? 'Teacher' }}</p>
              <p class="mt-1 text-xs text-slate-600">
                Duration: {{ $q->duration ? $q->duration.' min' : 'No timer' }}
              </p>

              <div class="mt-4">
                <a href="{{ route('quizzes.play', $q) }}"
                   class="inline-flex items-center px-4 py-2 rounded-xl font-semibold text-white shadow-sm hover:shadow-md transition"
                   style="background:{{ $cmBlue }};">
                  Start / Continue
                </a>
              </div>
            </div>
          @endforeach
        </div>
      @endif
    </div>

    {{-- Last quiz quick link --}}
    @if($lastQuiz)
      <div class="mt-5">
        <a href="{{ route('student.quizzes.show', $lastQuiz['attempt_id']) }}"
           class="inline-flex items-center px-4 py-2 rounded-xl font-semibold border border-slate-200 bg-white hover:shadow-sm transition">
          View last attempt details →
        </a>
      </div>
    @endif

  </div>
</div>
@endsection
