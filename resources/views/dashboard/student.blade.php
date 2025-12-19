@extends('layouts.app')

@section('content')
  @php
    $user = auth()->user();

    // Theme colors (same as Teacher)
    $cmBlue = '#2463EB';
    $cmGreen = '#8BDE63';
    $cmYellow = '#EDB70A';
    $cmRed = '#EF4444';

    // Values from controller (safe defaults)
    $attendanceCount = $attendanceCount ?? 0;
    $quizAttemptsCount = $quizAttemptsCount ?? 0;
    $lastQuiz = $lastQuiz ?? null;
    $activeQuizzes = $activeQuizzes ?? collect();

    $focusImg1 = asset('images/focus-1.png');
    $focusImg2 = asset('images/focus-2.png');
    $focusImg3 = asset('images/focus-3.png');

    // Progress (optional)
    $lastScoreProg = 0;
    if ($lastQuiz && ($lastQuiz['max'] ?? 0) > 0) {
      $lastScoreProg = max(0, min(100, ($lastQuiz['score'] / max(1, $lastQuiz['max'])) * 100));
    }
  @endphp

  <div class="min-h-[calc(100vh-88px)] text-slate-900" style="background: {{ $cmGreen }};">
    {{-- Top accent strip (same as Teacher) --}}
    <div class="h-[6px] w-full"
      style="background: linear-gradient(90deg, {{ $cmBlue }}, {{ $cmGreen }}, {{ $cmYellow }});"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">

      {{-- HERO --}}
      <section
        class="cm-hero cm-animate-in rounded-3xl border border-slate-200 bg-white overflow-hidden shadow-[0_10px_30px_rgba(15,23,42,0.06)]">
        <div class="relative">
          {{-- Soft ambient background blobs --}}
          <div class="absolute inset-0 pointer-events-none">
            <div class="absolute -top-24 -left-24 w-[520px] h-[520px] rounded-full blur-3xl"
              style="background: rgba(36,99,235,.10)"></div>
            <div class="absolute -bottom-24 -right-24 w-[560px] h-[560px] rounded-full blur-3xl"
              style="background: rgba(139,222,99,.12)"></div>
            <div class="absolute top-10 right-8 w-[360px] h-[360px] rounded-full blur-3xl"
              style="background: rgba(237,183,10,.10)"></div>
          </div>

          <div class="relative p-6 sm:p-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-center">
              {{-- Left: Title --}}
              <div class="lg:col-span-7">
                <p class="text-xs font-bold tracking-widest uppercase text-slate-500">Student Dashboard</p>
                <h1 class="mt-2 text-2xl sm:text-3xl lg:text-4xl font-extrabold leading-tight">
                  Welcome,
                  <span style="color: {{ $cmBlue }};">{{ $user->name }}</span>
                </h1>
                <p class="mt-2 text-sm sm:text-base text-slate-600 max-w-2xl">
                  A centralized view of your attendance, quiz participation, and academic performance.
                </p>

                {{-- Today’s Focus (3 images beside each other - CRISP version) --}}
                <div class="mt-5 hidden sm:block">
                  <div class="rounded-2xl border bg-white/70 p-4 overflow-hidden"
                       style="border-color: rgba(226,232,240,1);">

                    <div class="flex items-center justify-between gap-4">
                      <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-600">Today’s Focus</p>
                        <p class="mt-1 text-sm font-extrabold text-slate-900">
                          Show up, participate, and you’ll see results ✨
                        </p>
                      </div>

                      <div class="w-14 h-14 rounded-2xl grid place-items-center border bg-white"
                           style="border-color: rgba(36,99,235,.18);">
                        <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" style="color: {{ $cmBlue }};">
                          <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v6l4 2" />
                          <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                        </svg>
                      </div>
                    </div>

                    <div class="mt-4 rounded-2xl border bg-white p-3"
                         style="border-color: rgba(226,232,240,1);">
                      <div class="cm-focus-grid">
                        <div class="cm-focus-tile">
                          <img src="{{ $focusImg1 }}" alt="Track your progress" class="cm-focus-img" draggable="false">
                        </div>
                        <div class="cm-focus-tile">
                          <img src="{{ $focusImg2 }}" alt="Join attendance" class="cm-focus-img" draggable="false">
                        </div>
                        <div class="cm-focus-tile">
                          <img src="{{ $focusImg3 }}" alt="Give quizzes" class="cm-focus-img" draggable="false">
                        </div>
                      </div>
                    </div>

                  </div>
                </div>
              </div>

              {{-- Right: KPI strip (same count: 4) --}}
              <div class="lg:col-span-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  {{-- Attendance Marked --}}
                  <div class="cm-kpi cm-reveal"
                    style="background: rgba(139,222,99,.18); border-color: rgba(139,222,99,.28);">
                    <div class="flex items-center justify-between gap-3">
                      <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-700">Attendance Marked</p>
                        <p class="mt-1 text-3xl font-extrabold tabular-nums text-slate-900">{{ $attendanceCount }}</p>
                        <p class="mt-1 text-sm text-slate-800">Total check-ins</p>
                      </div>
                      <div class="cm-kpi-icon"
                        style="background: rgba(255,255,255,.9); border-color: rgba(139,222,99,.35);">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                          style="color: {{ $cmGreen }};">
                          <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M20 6L9 17l-5-5" />
                        </svg>
                      </div>
                    </div>
                  </div>

                  {{-- Quizzes Attempted --}}
                  <div class="cm-kpi cm-reveal"
                    style="background: rgba(36,99,235,.12); border-color: rgba(36,99,235,.22);">
                    <div class="flex items-center justify-between gap-3">
                      <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-700">Quizzes Attempted</p>
                        <p class="mt-1 text-3xl font-extrabold tabular-nums text-slate-900">{{ $quizAttemptsCount }}</p>
                        <p class="mt-1 text-sm text-slate-800">Submitted</p>
                      </div>
                      <div class="cm-kpi-icon"
                        style="background: rgba(255,255,255,.9); border-color: rgba(36,99,235,.25);">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                          style="color: {{ $cmBlue }};">
                          <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            d="M4 19V5M20 19H4M8 15v-4M12 15V9M16 15v-7" />
                        </svg>
                      </div>
                    </div>
                  </div>

                  {{-- Last Quiz Score --}}
                  <div class="cm-kpi cm-reveal"
                    style="background: rgba(237,183,10,.16); border-color: rgba(237,183,10,.26);">
                    <div class="flex items-center justify-between gap-3">
                      <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-700">Last Quiz Score</p>
                        <p class="mt-1 text-3xl font-extrabold tabular-nums text-slate-900">
                          {{ $lastQuiz ? $lastQuiz['score'] : '—' }}
                          @if($lastQuiz)
                            <span class="text-base text-slate-600 font-extrabold">/ {{ $lastQuiz['max'] }}</span>
                          @endif
                        </p>
                        <p class="mt-1 text-sm text-slate-800">
                          {{ $lastQuiz ? ($lastQuiz['title'] ?? 'Last attempt') : 'No attempts yet' }}
                        </p>
                      </div>

                      <div class="cm-kpi-icon"
                        style="background: rgba(255,255,255,.9); border-color: rgba(237,183,10,.30);">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                          style="color: {{ $cmYellow }};">
                          <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M8 21h8" />
                          <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 17v4" />
                          <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M7 4h10v3a5 5 0 0 1-10 0V4z" />
                          <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M7 6H5a2 2 0 0 0 2 2" />
                          <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M17 6h2a2 2 0 0 1-2 2" />
                        </svg>
                      </div>
                    </div>

                    @if($lastQuiz)
                      <div class="mt-3 h-2 rounded-full bg-white/80 overflow-hidden border border-white/60">
                        <div class="h-full rounded-full"
                          style="width: {{ $lastScoreProg }}%; background: {{ $cmYellow }};"></div>
                      </div>
                      <div class="mt-2 text-xs font-bold text-slate-700">
                        Keep going — aim a bit higher next time ✨
                      </div>
                    @endif
                  </div>

                  {{-- Profile --}}
                  <div class="cm-kpi cm-reveal"
                    style="background: rgba(36,99,235,.10); border-color: rgba(36,99,235,.18);">
                    <div class="flex items-center justify-between gap-3">
                      <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-700">Account</p>
                        <p class="mt-1 text-lg font-extrabold text-slate-900">Profile</p>
                        <p class="mt-1 text-sm text-slate-800">Update your info</p>
                      </div>
                      <div class="cm-kpi-icon"
                        style="background: rgba(255,255,255,.9); border-color: rgba(36,99,235,.22);">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                          style="color: {{ $cmBlue }};">
                          <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                          <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            d="M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8z" />
                        </svg>
                      </div>
                    </div>

                    <div class="mt-3 flex flex-wrap gap-2">
                      <a href="{{ route('profile.edit') }}" class="cm-btn-solid" style="--btn: {{ $cmBlue }};">
                        Open Profile
                      </a>
                    </div>
                  </div>

                </div>
              </div>
            </div>

          </div>
        </div>
      </section>

      {{-- MAIN CONTENT (Left column: 2 cards stacked + Right stacked cards) --}}
      <div class="mt-6 lg:mt-8 grid grid-cols-1 lg:grid-cols-12 gap-4 items-start">

        {{-- LEFT COLUMN: Available Quizzes (smaller) + Rules card (new) --}}
        <div class="lg:col-span-7 grid grid-cols-1 gap-4">

          {{-- LEFT: Available Quizzes --}}
          <section class="cm-panel cm-reveal" style="border-color: rgba(36,99,235,.18);">
            <div class="cm-panel-head" style="background: rgba(36,99,235,.08);">
              <div>
                <h2 class="text-xl font-extrabold">Available Quizzes</h2>
                <p class="mt-1 text-sm text-slate-600">Only active quizzes show here.</p>
              </div>
              <div class="w-12 h-12 rounded-2xl grid place-items-center bg-white/90 border"
                style="border-color: rgba(36,99,235,.18);">
                <span class="w-3 h-3 rounded-full" style="background: {{ $cmBlue }};"></span>
              </div>
            </div>

            <div class="cm-panel-body">
              @if(($activeQuizzes ?? collect())->count() === 0)
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 text-sm text-slate-600">
                  No active quizzes right now.
                </div>
              @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                  @foreach($activeQuizzes as $q)
                    <div class="cm-quiz-card rounded-2xl border border-slate-200 bg-white p-5">
                      <div class="flex items-start justify-between gap-3">
                        <div>
                          <p class="text-sm font-extrabold text-slate-900">{{ $q->title }}</p>
                          <p class="mt-1 text-xs text-slate-600">Teacher: {{ $q->teacher?->name ?? 'Teacher' }}</p>
                          <p class="mt-1 text-xs text-slate-600">
                            Duration: {{ $q->duration ? $q->duration . ' min' : 'No timer' }}
                          </p>
                        </div>
                        <div class="w-11 h-11 rounded-2xl grid place-items-center border bg-white"
                          style="border-color: rgba(226,232,240,1);">
                          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" style="color: {{ $cmBlue }};">
                            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                  d="M8 5h13M8 9h13M8 13h13M8 17h13" />
                            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 5h.01M3 9h.01M3 13h.01M3 17h.01" />
                          </svg>
                        </div>
                      </div>

                      <div class="mt-4 flex flex-wrap gap-2">
                        <a href="{{ route('quizzes.play', $q) }}" class="cm-btn-solid" style="--btn: {{ $cmBlue }};">
                          Start / Continue
                        </a>
                      </div>
                    </div>
                  @endforeach
                </div>
              @endif

              @if($lastQuiz)
                <div class="mt-4">
                  <a href="{{ route('student.quizzes.show', $lastQuiz['attempt_id']) }}" class="cm-btn-outline"
                    style="--btn: {{ $cmYellow }}; --btn-text: {{ $cmYellow }};">
                    View last attempt details →
                  </a>
                </div>
              @endif
            </div>
          </section>

          {{-- Rules & Guidelines (REMOVE crossed buttons here) --}}
          <section class="cm-panel cm-reveal" style="border-color: rgba(139,222,99,.20);">
            <div class="cm-panel-head" style="background: rgba(139,222,99,.10);">
              <div>
                <h2 class="text-xl font-extrabold">Rules & Guidelines</h2>
                <p class="mt-1 text-sm text-slate-600">Quick reminders for quizzes and attendance.</p>
              </div>
              <div class="w-12 h-12 rounded-2xl grid place-items-center bg-white/90 border"
                style="border-color: rgba(139,222,99,.25);">
                <span class="w-3 h-3 rounded-full" style="background: {{ $cmGreen }};"></span>
              </div>
            </div>

            <div class="cm-panel-body">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

                {{-- Quiz rules --}}
                <div class="rounded-2xl border p-5 bg-white"
                     style="border-color: rgba(36,99,235,.16);">
                  <div class="flex items-start justify-between gap-3">
                    <div>
                      <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Quiz Rules</p>
                      <p class="mt-1 text-sm font-extrabold text-slate-900">Attempt fairly & submit on time</p>
                    </div>
                    <div class="w-11 h-11 rounded-2xl grid place-items-center border bg-white"
                         style="border-color: rgba(36,99,235,.18);">
                      <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" style="color: {{ $cmBlue }};">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                              d="M9 12l2 2 4-4" />
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                              d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                      </svg>
                    </div>
                  </div>

                  <ul class="mt-3 space-y-2 text-sm text-slate-700">
                    <li class="flex gap-2">
                      <span class="mt-[6px] w-2 h-2 rounded-full" style="background: {{ $cmBlue }};"></span>
                      Read each question carefully before answering.
                    </li>
                    <li class="flex gap-2">
                      <span class="mt-[6px] w-2 h-2 rounded-full" style="background: {{ $cmBlue }};"></span>
                      If there is a timer, submit before time ends.
                    </li>
                    <li class="flex gap-2">
                      <span class="mt-[6px] w-2 h-2 rounded-full" style="background: {{ $cmBlue }};"></span>
                      Avoid refreshing during a timed quiz unless necessary.
                    </li>
                  </ul>
                </div>

                {{-- Attendance rules --}}
                <div class="rounded-2xl border p-5 bg-white"
                     style="border-color: rgba(237,183,10,.20);">
                  <div class="flex items-start justify-between gap-3">
                    <div>
                      <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Attendance Rules</p>
                      <p class="mt-1 text-sm font-extrabold text-slate-900">Mark only when instructed</p>
                    </div>
                    <div class="w-11 h-11 rounded-2xl grid place-items-center border bg-white"
                         style="border-color: rgba(237,183,10,.22);">
                      <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" style="color: {{ $cmYellow }};">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                              d="M20 6L9 17l-5-5" />
                      </svg>
                    </div>
                  </div>

                  <ul class="mt-3 space-y-2 text-sm text-slate-700">
                    <li class="flex gap-2">
                      <span class="mt-[6px] w-2 h-2 rounded-full" style="background: {{ $cmYellow }};"></span>
                      Join attendance during the active session window.
                    </li>
                    <li class="flex gap-2">
                      <span class="mt-[6px] w-2 h-2 rounded-full" style="background: {{ $cmYellow }};"></span>
                      Use the correct code/link shared by your teacher.
                    </li>
                    <li class="flex gap-2">
                      <span class="mt-[6px] w-2 h-2 rounded-full" style="background: {{ $cmYellow }};"></span>
                      Check your history if you think something is missing.
                    </li>
                  </ul>
                </div>

              </div>
            </div>
          </section>

        </div>

        {{-- RIGHT: stacked cards (make them smaller + stop stretching) --}}
        <section class="lg:col-span-5 cm-reveal">
          {{-- removed grid-rows-2 so cards don't become tall/empty --}}
          <div class="grid grid-cols-1 gap-4">

            {{-- Attendance Card --}}
            <div class="cm-panel cm-panel-compact" style="border-color: rgba(139,222,99,.18);">
              <div class="cm-panel-head cm-panel-head-compact" style="background: rgba(139,222,99,.10);">
                <div>
                  <h2 class="text-lg font-extrabold">Attendance</h2>
                  <p class="mt-1 text-xs text-slate-600">Join sessions and review your history.</p>
                </div>
                <div class="w-10 h-10 rounded-2xl grid place-items-center bg-white/90 border"
                  style="border-color: rgba(139,222,99,.25);">
                  <span class="w-2.5 h-2.5 rounded-full" style="background: {{ $cmGreen }};"></span>
                </div>
              </div>

              <div class="cm-panel-body cm-panel-body-compact">
                <div class="rounded-2xl border bg-white p-4"
                  style="border-color: rgba(226,232,240,1);">
                  <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Your Stats</p>

                  <div class="mt-3 grid grid-cols-2 gap-3">
                    <div class="rounded-2xl border p-3"
                      style="border-color: rgba(139,222,99,.22); background: rgba(139,222,99,.10);">
                      <p class="text-[10px] font-bold uppercase tracking-wider text-slate-600">Marked</p>
                      <p class="mt-1 text-xl font-extrabold tabular-nums text-slate-900">{{ $attendanceCount }}</p>
                      <p class="mt-1 text-[11px] text-slate-700">Total</p>
                    </div>

                    <div class="rounded-2xl border p-3"
                      style="border-color: rgba(36,99,235,.18); background: rgba(36,99,235,.06);">
                      <p class="text-[10px] font-bold uppercase tracking-wider text-slate-600">Streak</p>
                      <p class="mt-1 text-xl font-extrabold tabular-nums text-slate-900">—</p>
                      <p class="mt-1 text-[11px] text-slate-700">Coming soon</p>
                    </div>
                  </div>

                  <div class="mt-4 flex flex-wrap gap-2">
                    <a href="{{ route('attendance.join.form') }}" class="cm-btn-solid cm-btn-compact"
                      style="--btn: {{ $cmYellow }}; --btn-text:#FFFFFF;">
                      Join Attendance
                    </a>

                    <a href="{{ route('student.attendance.history') }}" class="cm-btn-outline cm-btn-compact"
                      style="--btn: {{ $cmBlue }}; --btn-text:#2463EB;">
                      Attendance History
                    </a>
                  </div>
                </div>
              </div>
            </div>

            {{-- History Card --}}
            <div class="cm-panel cm-panel-compact" style="border-color: rgba(237,183,10,.18);">
              <div class="cm-panel-head cm-panel-head-compact" style="background: rgba(237,183,10,.10);">
                <div>
                  <h2 class="text-lg font-extrabold">Your Progress</h2>
                  <p class="mt-1 text-xs text-slate-600">Review quiz history and improve steadily.</p>
                </div>
                <span class="text-[11px] font-bold text-slate-500">Overview</span>
              </div>

              <div class="cm-panel-body cm-panel-body-compact">
                <div class="rounded-2xl border bg-white p-4"
                  style="border-color: rgba(226,232,240,1);">
                  <div class="flex items-start justify-between gap-3">
                    <div>
                      <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Quiz Attempts</p>
                      <p class="mt-1 text-2xl font-extrabold tabular-nums text-slate-900">{{ $quizAttemptsCount }}</p>
                    
                    </div>

                    <div class="w-10 h-10 rounded-2xl grid place-items-center border bg-white"
                      style="border-color: rgba(237,183,10,.25);">
                      <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" style="color: {{ $cmYellow }};">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                              d="M9 12l2 2 4-4" />
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                              d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                      </svg>
                    </div>
                  </div>

                  @if($lastQuiz)
                    <div class="mt-3 rounded-2xl border p-3"
                      style="border-color: rgba(237,183,10,.22); background: rgba(237,183,10,.08);">
                      <p class="text-[10px] font-bold uppercase tracking-wider text-slate-600">Last Result</p>
                      <p class="mt-1 text-sm font-extrabold text-slate-900">
                        {{ $lastQuiz['title'] ?? 'Last attempt' }}
                      </p>
                      <div class="mt-2 flex items-center justify-between">
                        <p class="text-sm font-extrabold text-slate-900">
                          {{ $lastQuiz['score'] }} <span class="text-slate-600">/ {{ $lastQuiz['max'] }}</span>
                        </p>
                        <span class="text-[11px] font-bold text-slate-700">Score</span>
                      </div>

                      <div class="mt-3 h-2 rounded-full bg-white/85 overflow-hidden border border-white/70">
                        <div class="h-full rounded-full" style="width: {{ $lastScoreProg }}%; background: {{ $cmYellow }};"></div>
                      </div>
                    </div>
                  @endif

                  <div class="mt-4 flex flex-wrap gap-2">
                    <a href="{{ route('student.quizzes.history') }}" class="cm-btn-solid cm-btn-compact"
                      style="--btn: {{ $cmBlue }}; --btn-text:#FFFFFF;">
                      Quiz History
                    </a>

                    @if($lastQuiz)
                      <a href="{{ route('student.quizzes.show', $lastQuiz['attempt_id']) }}" class="cm-btn-outline cm-btn-compact"
                        style="--btn: {{ $cmYellow }}; --btn-text: {{ $cmYellow }};">
                        View last attempt →
                      </a>
                    @endif
                  </div>
                </div>
              </div>
            </div>

          </div>
        </section>
      </div>

      {{-- Styles --}}
      <style>
        .cm-animate-in { opacity: 0; transform: translateY(10px); animation: cmIn .55s ease forwards; }
        @keyframes cmIn { to { opacity: 1; transform: translateY(0); } }

        .cm-reveal { opacity: 0; transform: translateY(14px); }
        .cm-reveal.cm-in { opacity: 1; transform: translateY(0); transition: opacity .55s ease, transform .55s ease; }

        .cm-kpi {
          border: 1px solid rgba(226, 232, 240, 1);
          border-radius: 1.25rem;
          padding: .85rem;
          box-shadow: 0 1px 0 rgba(15, 23, 42, .04);
          transition: transform .2s ease, box-shadow .2s ease;
        }
        .cm-kpi:hover { transform: translateY(-2px); box-shadow: 0 14px 34px rgba(15, 23, 42, .08); }
        .cm-kpi-icon {
          width: 40px;
          height: 40px;
          border-radius: 14px;
          display: grid;
          place-items: center;
          border: 1px solid rgba(226, 232, 240, 1);
        }

        .cm-panel {
          border: 1px solid rgba(226, 232, 240, 1);
          background: #fff;
          border-radius: 1.5rem;
          box-shadow: 0 10px 30px rgba(15, 23, 42, .06);
          overflow: hidden;
        }
        .cm-panel-head {
          padding: 1rem 1rem .85rem 1rem;
          border-bottom: 1px solid rgba(226, 232, 240, 1);
          display: flex;
          align-items: flex-start;
          justify-content: space-between;
          gap: .85rem;
        }
        .cm-panel-body { padding: 1rem; }

        /* compact right cards */
        .cm-panel-head-compact { padding: .85rem .9rem .75rem .9rem; }
        .cm-panel-body-compact { padding: .9rem; }

        .cm-btn-solid {
          display: inline-flex;
          align-items: center;
          justify-content: center;
          padding: .62rem 1rem;
          border-radius: 9999px;
          border: 2px solid transparent;
          background: var(--btn, #2463EB);
          color: var(--btn-text, #fff);
          font-weight: 950;
          box-shadow: 0 6px 16px rgba(15, 23, 42, .10);
          transition: transform .15s ease, box-shadow .2s ease, filter .2s ease;
          white-space: nowrap;
        }
        .cm-btn-solid:hover {
          transform: translateY(-1px);
          box-shadow: 0 12px 26px rgba(15, 23, 42, .14);
          filter: saturate(1.06);
        }

        .cm-btn-outline {
          --btn: #2463EB;
          --btn-text: var(--btn);
          display: inline-flex;
          align-items: center;
          justify-content: center;
          padding: .62rem 1rem;
          border-radius: 9999px;
          border: 2px solid var(--btn);
          background: #fff;
          color: var(--btn-text);
          font-weight: 950;
          box-shadow: 0 6px 16px rgba(15, 23, 42, .08);
          transition: transform .15s ease, box-shadow .2s ease, background .2s ease;
          white-space: nowrap;
        }
        .cm-btn-outline:hover {
          transform: translateY(-1px);
          box-shadow: 0 12px 26px rgba(15, 23, 42, .12);
          background: rgba(255, 255, 255, .95);
        }

        /* smaller buttons for right cards only */
        .cm-btn-compact{
          padding: .50rem .90rem !important;
          font-size: .875rem;
          font-weight: 900;
        }

        .cm-quiz-card { box-shadow: 0 1px 0 rgba(15,23,42,.04); transition: transform .18s ease, box-shadow .2s ease; }
        .cm-quiz-card:hover { transform: translateY(-2px); box-shadow: 0 14px 34px rgba(15,23,42,.08); }

        /* Today’s Focus images (NO FADE) */
        .cm-focus-grid{
          display: grid;
          grid-template-columns: repeat(3, minmax(0, 1fr));
          gap: 14px;
          align-items: stretch;
        }

        .cm-focus-tile{
          border-radius: 18px;
          border: 1px solid rgba(226,232,240,1);
          background: #d6e4ffff; /* change this to change the 3 images background tile color */
          overflow: hidden;
          height: 140px;
          display: grid;
          place-items: center;
        }

        .cm-focus-img{
          width: 100%;
          height: 100%;
          object-fit: contain;
          object-position: center;
          transform: translateZ(0);
          image-rendering: auto;
          -webkit-font-smoothing: antialiased;
        }
      </style>

      {{-- JS: reveal --}}
      <script>
        (function () {
          const items = Array.from(document.querySelectorAll('.cm-reveal'));
          const io = new IntersectionObserver((entries) => {
            entries.forEach(e => {
              if (e.isIntersecting) {
                e.target.classList.add('cm-in');
                io.unobserve(e.target);
              }
            });
          }, { threshold: 0.10 });
          items.forEach(el => io.observe(el));
        })();
      </script>

    </div>
  </div>
@endsection
