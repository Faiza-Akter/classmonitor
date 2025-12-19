@extends('layouts.app')

@section('content')
    @php
        $user = auth()->user();
        $teacherName = $user?->name ?? 'Teacher';

        // Theme colors
        $cmBlue = '#2463EB';
        $cmGreen = '#8BDE63';
        $cmYellow = '#EDB70A';

        // Extra accents (optional)
        $cmRed = '#EF4444';

        // Values from TeacherDashboardController
        $todayCheckins = $todayCheckins ?? 0;
        $todaySessions = $todaySessions ?? 0;
        $avgScore = $avgScore ?? null;
        $pendingReviews = $pendingReviews ?? 0;

        // Attendance
        $activeAttendance = $activeAttendance ?? null;
        $liveCheckins = $liveCheckins ?? 0;

        // Quiz
        $activeQuiz = $activeQuiz ?? null;

        // Progress values
        $attProg = max(0, min(100, ($todaySessions > 0 ? ($todayCheckins / max(1, $todaySessions)) * 18 : 12)));
        $scoreProg = $avgScore === null ? 0 : max(0, min(100, ($avgScore / 10) * 100));
    @endphp

    <div class="min-h-[calc(100vh-88px)] text-slate-900" style="background:#2463EB;">
        {{-- Top accent strip --}}
        <div class="h-[6px] w-full"
            style="background: linear-gradient(90deg, {{ $cmBlue }}, {{ $cmGreen }}, {{ $cmYellow }});"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">

            {{-- HERO --}}
            <section
                class="cm-hero cm-animate-in rounded-3xl border border-slate-200 bg-white overflow-hidden shadow-[0_10px_30px_rgba(15,23,42,0.06)]">
                <div class="relative">
                    {{-- Soft ambient background --}}
                    <div class="absolute inset-0 pointer-events-none">
                        <div class="absolute -top-24 -left-24 w-[520px] h-[520px] rounded-full blur-3xl"
                            style="background: rgba(36,99,235,.10)"></div>
                        <div class="absolute -bottom-24 -right-24 w-[560px] h-[560px] rounded-full blur-3xl"
                            style="background: rgba(139,222,99,.12)"></div>
                        <div class="absolute top-10 right-8 w-[360px] h-[360px] rounded-full blur-3xl"
                            style="background: rgba(237,183,10,.10)"></div>
                    </div>

                    <div class="relative p-6 sm:p-8">
                        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                            <div>
                                <p class="text-xs font-bold tracking-widest uppercase text-slate-500">Teacher Dashboard</p>
                                <h1 class="mt-2 text-2xl sm:text-3xl lg:text-4xl font-extrabold leading-tight">
                                    Welcome back,
                                    <span style="color: {{ $cmBlue }};">{{ $teacherName }}</span>
                                </h1>
                                <p class="mt-2 text-sm sm:text-base text-slate-600 max-w-2xl">
                                    Run attendance, launch quizzes, and review performance — fast, clean, and organized.
                                </p>
                            </div>

                            {{-- Quick actions --}}
                            <div class="flex flex-wrap gap-2 sm:gap-3">
                                <a href="{{ route('profile.edit') }}" class="cm-chip cm-chip-primary"
                                    style="background: {{ $cmBlue }}; border-color: rgba(36,99,235,.35);">
                                    Profile
                                </a>
                            </div>
                        </div>

                        {{-- KPI STRIP (reduced height) --}}
                        <div class="mt-6 sm:mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                            {{-- Attendance Today --}}
                            <div class="cm-kpi cm-reveal"
                                style="background: rgba(139,222,99,.18); border-color: rgba(139,222,99,.28);">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-wider text-slate-700">Attendance
                                            Today</p>
                                        <p class="mt-1 text-3xl font-extrabold tabular-nums text-slate-900">
                                            {{ $todayCheckins }}
                                        </p>
                                        <p class="mt-1 text-sm text-slate-800">
                                            Sessions: <span class="font-extrabold">{{ $todaySessions }}</span>
                                        </p>
                                    </div>
                                    <div class="cm-kpi-icon"
                                        style="background: rgba(255,255,255,.9); border-color: rgba(139,222,99,.35);">
                                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            style="color: {{ $cmGreen }};">
                                            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                d="M20 6L9 17l-5-5" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="mt-3 h-2 rounded-full bg-white/80 overflow-hidden border border-white/60">
                                    <div class="h-full rounded-full"
                                        style="width: {{ $attProg }}%; background: {{ $cmGreen }};"></div>
                                </div>
                            </div>

                            {{-- Avg Quiz Score --}}
                            <div class="cm-kpi cm-reveal"
                                style="background: rgba(36,99,235,.12); border-color: rgba(36,99,235,.22);">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-wider text-slate-700">Avg Quiz Score
                                        </p>
                                        <p class="mt-1 text-3xl font-extrabold tabular-nums text-slate-900">
                                            {{ $avgScore === null ? '—' : number_format($avgScore, 1) }}
                                        </p>
                                        <p class="mt-1 text-sm text-slate-800">Submitted attempts</p>
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
                                <div class="mt-3 h-2 rounded-full bg-white/80 overflow-hidden border border-white/60">
                                    <div class="h-full rounded-full"
                                        style="width: {{ $scoreProg }}%; background: {{ $cmBlue }};"></div>
                                </div>
                            </div>

                            {{-- Pending Reviews --}}
                            <div class="cm-kpi cm-reveal"
                                style="background: rgba(237,183,10,.16); border-color: rgba(237,183,10,.26);">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-wider text-slate-700">Pending Reviews
                                        </p>
                                        <p class="mt-1 text-3xl font-extrabold tabular-nums text-slate-900">
                                            {{ $pendingReviews }}
                                        </p>
                                        <p class="mt-1 text-sm text-slate-800">Short answers</p>
                                    </div>
                                    <div class="cm-kpi-icon"
                                        style="background: rgba(255,255,255,.9); border-color: rgba(237,183,10,.30);">
                                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            style="color: {{ $cmYellow }};">
                                            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 5h6m-6 0a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2m-6 0a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="mt-3 text-xs text-slate-800">
                                    Tip: Review pending answers to keep results accurate.
                                </div>
                            </div>

                            {{-- Reports --}}
                            <div class="cm-kpi cm-reveal"
                                style="background: rgba(36,99,235,.10); border-color: rgba(36,99,235,.18);">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-wider text-slate-700">Reports</p>
                                        <p class="mt-1 text-lg font-extrabold text-slate-900">Export</p>
                                        <p class="mt-1 text-sm text-slate-800">Attendance + quiz summaries</p>
                                    </div>
                                    <div class="cm-kpi-icon"
                                        style="background: rgba(255,255,255,.9); border-color: rgba(36,99,235,.22);">
                                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            style="color: {{ $cmBlue }};">
                                            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 3v10m0 0l4-4m-4 4l-4-4M4 17v3h16v-3" />
                                        </svg>
                                    </div>
                                </div>

                                <div class="mt-3 flex flex-wrap gap-2">
                                    <a href="{{ route('attendance.export.csv') }}" class="cm-btn-solid"
                                        style="--btn: {{ $cmBlue }};">
                                        Export CSV
                                    </a>

                                    <a href="{{ route('attendance.index') }}" class="cm-btn-outline"
                                        style="--btn: {{ $cmGreen }}; --btn-text: #8BDE63;">
                                        History
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </section>

            {{-- MAIN CONTENT --}}
            <div class="mt-6 lg:mt-8 grid grid-cols-1 lg:grid-cols-12 gap-5 items-stretch">

                {{-- LEFT: Attendance panel --}}
                <section class="lg:col-span-7 cm-panel cm-reveal h-full flex flex-col"
                    style="border-color: rgba(139,222,99,.18);">
                    <div class="cm-panel-head" style="background: rgba(139,222,99,.10);">
                        <div>
                            <h2 class="text-xl font-extrabold">Attendance Control</h2>
                            <p class="mt-1 text-sm text-slate-600">Create sessions, share code, track check-ins live.</p>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <form method="POST" action="{{ route('attendance.sessions.create') }}">
                                @csrf
                                <input type="hidden" name="expires_minutes" value="10">
                                <button type="submit" class="cm-btn-solid"
                                    style="--btn: {{ $cmYellow }}; --btn-text:#FFFFFF;">
                                    + New Session
                                </button>
                            </form>

                            <a href="{{ route('attendance.index') }}" class="cm-btn-outline" style="--btn: {{ $cmBlue }};">
                                Open
                            </a>
                        </div>
                    </div>

                    {{-- make body stretch --}}
                    <div class="cm-panel-body flex-1">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">

                            {{-- Session code --}}
                            <div class="md:col-span-7 rounded-2xl border border-slate-200 bg-white p-5">
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Current Session Code
                                </p>

                                <div class="mt-3 flex items-center gap-3 flex-wrap">
                                    <div class="text-3xl font-extrabold tracking-[0.25em] tabular-nums">
                                        {{ $activeAttendance?->session_code ?? '— — — —' }}
                                    </div>

                                    @if($activeAttendance?->session_code)
                                        <button type="button" class="cm-mini-outline" style="--btn: {{ $cmBlue }};"
                                            data-copy="{{ $activeAttendance->session_code }}"
                                            onclick="event.preventDefault(); event.stopPropagation();">
                                            Copy
                                        </button>
                                    @endif
                                </div>

                                @if($activeAttendance?->expires_at)
                                    <p class="mt-2 text-sm text-slate-600">
                                        Expires at: <span
                                            class="font-semibold">{{ $activeAttendance->expires_at->format('h:i A') }}</span>
                                    </p>
                                @else
                                    <p class="mt-2 text-sm text-slate-600">Create a session to generate a code.</p>
                                @endif

                                @if($activeAttendance)
                                    <div class="mt-4 flex flex-wrap gap-2">
                                        <a href="{{ route('attendance.index') }}" class="cm-btn-outline"
                                            style="--btn: {{ $cmGreen }}; --btn-text:#8BDE63;">
                                            View Sessions
                                        </a>

                                        <form method="POST" action="{{ route('attendance.sessions.end', $activeAttendance) }}">
                                            @csrf
                                            <button type="submit" class="cm-btn-solid" style="--btn: {{ $cmRed }};">
                                                End Session
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>

                            {{-- Live check-ins --}}
                            <div class="md:col-span-5 rounded-2xl border p-5"
                                style="background: rgba(139,222,99,.12); border-color: rgba(139,222,99,.20);">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-extrabold">Live Check-ins</p>
                                    <span class="inline-flex items-center gap-2 text-xs font-bold text-slate-700">
                                        <span class="w-2.5 h-2.5 rounded-full" style="background: {{ $cmGreen }};"></span>
                                        Live
                                    </span>
                                </div>

                                <div class="mt-4 flex items-end justify-between">
                                    <div class="text-5xl font-extrabold tabular-nums leading-none" id="liveCheckins">
                                        {{ $liveCheckins ?? 0 }}
                                    </div>

                                    <div class="w-14 h-14 rounded-2xl grid place-items-center bg-white/90 border"
                                        style="border-color: rgba(139,222,99,.25);">
                                        <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            style="color: {{ $cmGreen }};">
                                            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 20v-6m0 0l3 3m-3-3l-3 3M6 4h12a2 2 0 0 1 2 2v4" />
                                        </svg>
                                    </div>
                                </div>

                                <div class="mt-4 rounded-xl bg-white/85 border p-3"
                                    style="border-color: rgba(139,222,99,.18);">
                                    <p class="text-xs text-slate-700">
                                        Tip: Keep the session active during class. End it when class is over.
                                    </p>
                                </div>
                            </div>

                            {{-- Tips / Illustration --}}
                            <div class="md:col-span-12 rounded-2xl border p-5 overflow-hidden"
                                style="border-color: rgba(36,99,235,.14); background: rgba(36,99,235,.05);">
                                <div class="flex flex-col md:flex-row md:items-center gap-5">
                                    <div class="flex-1">
                                        <p class="text-xs font-bold uppercase tracking-wider text-slate-600">Quick Tips</p>
                                        <h3 class="mt-1 text-lg font-extrabold text-slate-900">Keep attendance smooth</h3>
                                        <ul class="mt-3 space-y-2 text-sm text-slate-700">
                                            <li class="flex gap-2">
                                                <span class="mt-1 w-2 h-2 rounded-full"
                                                    style="background: {{ $cmBlue }};"></span>
                                                Start a new session before class begins.
                                            </li>
                                            <li class="flex gap-2">
                                                <span class="mt-1 w-2 h-2 rounded-full"
                                                    style="background: {{ $cmGreen }};"></span>
                                                Share the session code and monitor live check-ins.
                                            </li>
                                            <li class="flex gap-2">
                                                <span class="mt-1 w-2 h-2 rounded-full"
                                                    style="background: {{ $cmYellow }};"></span>
                                                End the session right after class to prevent late entries.
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="md:w-[320px] w-full">
                                        <div class="rounded-2xl bg-white border p-4"
                                            style="border-color: rgba(36,99,235,.14);">
                                            <svg viewBox="0 0 420 220" class="w-full h-[160px]">
                                                <rect x="10" y="20" width="400" height="160" rx="18"
                                                    fill="rgba(36,99,235,.08)" />
                                                <rect x="30" y="45" width="200" height="18" rx="9"
                                                    fill="rgba(36,99,235,.25)" />
                                                <rect x="30" y="75" width="300" height="12" rx="6"
                                                    fill="rgba(15,23,42,.10)" />
                                                <rect x="30" y="98" width="260" height="12" rx="6"
                                                    fill="rgba(15,23,42,.08)" />
                                                <rect x="30" y="121" width="220" height="12" rx="6"
                                                    fill="rgba(15,23,42,.06)" />
                                                <circle cx="355" cy="65" r="22" fill="rgba(139,222,99,.35)" />
                                                <path d="M347 65l6 6 12-14" fill="none" stroke="rgba(15,23,42,.75)"
                                                    stroke-width="5" stroke-linecap="round" stroke-linejoin="round" />
                                                <rect x="30" y="150" width="140" height="22" rx="11"
                                                    fill="rgba(237,183,10,.30)" />
                                                <rect x="178" y="150" width="110" height="22" rx="11"
                                                    fill="rgba(36,99,235,.30)" />
                                            </svg>

                                            <div class="mt-2 text-xs font-bold text-slate-600">“Ready” checklist for every
                                                class</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </section>

                {{-- RIGHT: make it stretch to same height as LEFT --}}
                <section class="lg:col-span-5 cm-reveal h-full flex flex-col gap-5">

                    {{-- Quiz control (stretchable) --}}
                    <div class="cm-panel flex-1 flex flex-col" style="border-color: rgba(237,183,10,.18);">
                        <div class="cm-panel-head" style="background: rgba(237,183,10,.10);">
                            <div>
                                <h2 class="text-xl font-extrabold">Quiz Center</h2>
                                <p class="mt-1 text-sm text-slate-600">Open quizzes, view results, and grading tools.</p>
                            </div>
                            <div class="w-12 h-12 rounded-2xl grid place-items-center bg-white/90 border"
                                style="border-color: rgba(237,183,10,.25);">
                                <span class="w-3 h-3 rounded-full" style="background: {{ $cmYellow }};"></span>
                            </div>
                        </div>

                        <div class="cm-panel-body flex-1">
                            <div class="rounded-2xl border border-slate-200 bg-white p-5 h-full">
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Active Quiz</p>
                                <p class="mt-2 text-lg font-extrabold text-slate-900">
                                    {{ $activeQuiz?->title ?? 'None running' }}
                                </p>

                                <div class="mt-4 flex flex-wrap gap-2">
                                    <a href="{{ route('quizzes.index') }}" class="cm-btn-solid"
                                        style="--btn: {{ $cmBlue }}; --btn-text:#FFFFFF;">
                                        Open Quizzes
                                    </a>

                                    @if($activeQuiz)
                                        <a href="{{ route('quizzes.results', $activeQuiz) }}" class="cm-btn-outline"
                                            style="--btn: {{ $cmGreen }}; --btn-text:#8BDE63;">
                                            Results
                                        </a>
                                    @else
                                        <button type="button" class="cm-btn-outline opacity-50 cursor-not-allowed"
                                            style="--btn: {{ $cmYellow }}; --btn-text:#000;" disabled>
                                            Results
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Performance snapshot (stretchable) --}}
                    <div class="cm-panel flex-1 flex flex-col" style="border-color: rgba(36,99,235,.18);">
                        <div class="cm-panel-head" style="background: rgba(36,99,235,.08);">
                            <div>
                                <h2 class="text-xl font-extrabold">Performance Snapshot</h2>
                                <p class="mt-1 text-sm text-slate-600">Quick insight into classroom performance.</p>
                            </div>
                            <span class="text-xs font-bold text-slate-500">Overview</span>
                        </div>

                        <div class="cm-panel-body flex-1">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div class="cm-mini-card"
                                    style="background: rgba(36,99,235,.08); border-color: rgba(36,99,235,.14);">
                                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-600">Avg Score</p>
                                    <p class="mt-1 text-2xl font-extrabold tabular-nums">
                                        {{ $avgScore === null ? '—' : number_format($avgScore, 1) }}
                                    </p>
                                    <div class="mt-3 h-2 rounded-full bg-white/85 overflow-hidden border border-white/70">
                                        <div class="h-full rounded-full"
                                            style="width: {{ $scoreProg }}%; background: {{ $cmBlue }};"></div>
                                    </div>
                                </div>

                                <div class="cm-mini-card"
                                    style="background: rgba(237,183,10,.10); border-color: rgba(237,183,10,.14);">
                                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-600">Pending</p>
                                    <p class="mt-1 text-2xl font-extrabold tabular-nums">{{ $pendingReviews }}</p>
                                    <p class="mt-2 text-xs text-slate-700">Manual review needed</p>
                                </div>

                                <div class="cm-mini-card"
                                    style="background: rgba(139,222,99,.12); border-color: rgba(139,222,99,.14);">
                                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-600">Today</p>
                                    <p class="mt-1 text-2xl font-extrabold tabular-nums">{{ $todayCheckins }}</p>
                                    <p class="mt-2 text-xs text-slate-700">Total check-ins</p>
                                </div>
                            </div>

                            <div class="mt-4 flex flex-wrap gap-2">
                                <a href="{{ route('quizzes.grading.index', $activeQuiz ?? 0) }}"
                                    class="cm-btn-solid {{ $activeQuiz ? '' : 'pointer-events-none opacity-50' }}"
                                    style="--btn: {{ $cmGreen }}; --btn-text:#FFFFFF;">
                                    Manual Grading
                                </a>

                                @if($activeQuiz)
                                    <a href="{{ route('quizzes.leaderboard', $activeQuiz) }}" class="cm-btn-outline"
                                        style="--btn: {{ $cmYellow }}; --btn-text:#EDB70A;">
                                        Leaderboard
                                    </a>
                                @else
                                    <button type="button" class="cm-btn-outline opacity-50 cursor-not-allowed"
                                        style="--btn: {{ $cmYellow }}; --btn-text:#EDB70A;" disabled>
                                        Leaderboard
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                </section>
            </div>


            {{-- Styles --}}
            <style>
                .cm-animate-in {
                    opacity: 0;
                    transform: translateY(10px);
                    animation: cmIn .55s ease forwards;
                }

                @keyframes cmIn {
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }

                .cm-reveal {
                    opacity: 0;
                    transform: translateY(14px);
                }

                .cm-reveal.cm-in {
                    opacity: 1;
                    transform: translateY(0);
                    transition: opacity .55s ease, transform .55s ease;
                }

                .cm-chip {
                    display: inline-flex;
                    align-items: center;
                    gap: .6rem;
                    padding: .7rem 1rem;
                    border-radius: 9999px;
                    border: 1px solid rgba(226, 232, 240, 1);
                    background: rgba(255, 255, 255, 1);
                    color: #0f172a;
                    font-weight: 900;
                    box-shadow: 0 1px 0 rgba(15, 23, 42, .04);
                    transition: transform .15s ease, box-shadow .2s ease;
                }

                .cm-chip:hover {
                    transform: translateY(-1px);
                    box-shadow: 0 12px 26px rgba(15, 23, 42, .08);
                }

                .cm-chip-primary {
                    color: #fff;
                }

                /* KPI height reduced */
                .cm-kpi {
                    border: 1px solid rgba(226, 232, 240, 1);
                    border-radius: 1.25rem;
                    padding: .85rem;
                    /* was 1.1rem */
                    box-shadow: 0 1px 0 rgba(15, 23, 42, .04);
                    transition: transform .2s ease, box-shadow .2s ease;
                }

                .cm-kpi:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 14px 34px rgba(15, 23, 42, .08);
                }

                .cm-kpi-icon {
                    width: 40px;
                    height: 40px;
                    /* slightly smaller */
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
                    padding: 1.25rem 1.25rem 1rem 1.25rem;
                    border-bottom: 1px solid rgba(226, 232, 240, 1);
                    display: flex;
                    align-items: flex-start;
                    justify-content: space-between;
                    gap: 1rem;
                }

                .cm-panel-body {
                    padding: 1.25rem;
                }

                /* SOLID */
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

                /* OUTLINE (keep yours) */
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
            </style>

            {{-- JS: reveal + copy --}}
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

                    document.addEventListener('click', async (ev) => {
                        const btn = ev.target.closest('[data-copy]');
                        if (!btn) return;
                        try {
                            await navigator.clipboard.writeText(btn.getAttribute('data-copy') || '');
                            const old = btn.textContent;
                            btn.textContent = 'Copied ✓';
                            setTimeout(() => btn.textContent = old, 1100);
                        } catch (e) { }
                    });
                })();
            </script>

            {{-- Optional: live polling --}}
            @if($activeAttendance)
                <script>
                    const liveUrl = @json(route('attendance.sessions.live', $activeAttendance));
                    async function pollLiveCount() {
                        try {
                            const res = await fetch(liveUrl, { headers: { "Accept": "application/json" } });
                            const data = await res.json();
                            const el = document.getElementById('liveCheckins');
                            if (el) el.textContent = data.count ?? 0;
                        } catch (e) { }
                    }
                    pollLiveCount();
                    setInterval(pollLiveCount, 4000);
                </script>
            @endif
        </div>
@endsection