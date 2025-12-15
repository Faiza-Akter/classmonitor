@extends('layouts.app')

@section('content')
@php
    $user = auth()->user();
    $teacherName = $user?->name ?? 'Teacher';

    // Theme colors
    $cmBlue   = '#2463EB';
    $cmGreen  = '#8BDE63';
    $cmYellow = '#EDB70A';

    // Safe defaults (wire later)
    $todayCheckins   = $todayCheckins ?? 0;
    $todaySessions   = $todaySessions ?? 0;
    $avgScore        = $avgScore ?? null;
    $pendingReviews  = $pendingReviews ?? 0;
@endphp

<div class="relative min-h-[calc(100vh-88px)] overflow-hidden bg-white text-slate-900">

    {{-- Gradient background --}}
    <div class="absolute inset-0">
        <div class="absolute inset-0"
             style="background: radial-gradient(1200px 600px at 10% 10%, rgba(36,99,235,.16), transparent 60%),
                            radial-gradient(900px 520px at 90% 20%, rgba(237,183,10,.14), transparent 55%),
                            radial-gradient(1000px 700px at 70% 95%, rgba(139,222,99,.14), transparent 60%),
                            linear-gradient(180deg, rgba(255,255,255,1), rgba(248,250,252,1));">
        </div>
    </div>

    <div class="relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">

            {{-- Header --}}
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between mb-6 lg:mb-8">
                <div>
                    <p class="text-sm font-semibold text-slate-600">Teacher Dashboard</p>
                    <h1 class="mt-1 text-2xl sm:text-3xl font-extrabold text-slate-900">
                        Welcome back, <span style="color:{{ $cmBlue }};">{{ $teacherName }}</span>
                    </h1>
                    <p class="mt-1 text-sm text-slate-600">
                        Quick access to attendance and quizzes.
                    </p>
                </div>

                <div class="flex flex-wrap gap-2">
                    <a href="{{ url('/attendance') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl font-semibold border border-slate-200 bg-white
                              shadow-sm hover:shadow-md transition">
                        <span class="inline-block w-2.5 h-2.5 rounded-full" style="background:{{ $cmGreen }};"></span>
                        Attendance
                    </a>

                    <a href="{{ url('/quizzes') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl font-semibold border border-slate-200 bg-white
                              shadow-sm hover:shadow-md transition">
                        <span class="inline-block w-2.5 h-2.5 rounded-full" style="background:{{ $cmYellow }};"></span>
                        Quizzes
                    </a>

                    <a href="{{ route('profile.edit') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl font-semibold text-white shadow-sm hover:shadow-md transition"
                       style="background:{{ $cmBlue }};">
                        Profile
                    </a>
                </div>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-5 mb-6 lg:mb-8">

                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm hover:shadow-md transition p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-600">Attendance Today</p>
                            <p class="mt-1 text-3xl font-extrabold text-slate-900">{{ $todayCheckins }}</p>
                            <p class="mt-2 text-xs text-slate-500">Sessions: {{ $todaySessions }}</p>
                        </div>
                        <div class="w-11 h-11 rounded-2xl grid place-items-center" style="background:rgba(139,222,99,.18);">
                            <span class="w-2.5 h-2.5 rounded-full" style="background:{{ $cmGreen }};"></span>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm hover:shadow-md transition p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-600">Avg Quiz Score</p>
                            <p class="mt-1 text-3xl font-extrabold text-slate-900">
                                {{ $avgScore === null ? '—' : number_format($avgScore, 1) }}
                            </p>
                            <p class="mt-2 text-xs text-slate-500">Recent attempts</p>
                        </div>
                        <div class="w-11 h-11 rounded-2xl grid place-items-center" style="background:rgba(36,99,235,.14);">
                            <span class="w-2.5 h-2.5 rounded-full" style="background:{{ $cmBlue }};"></span>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm hover:shadow-md transition p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-600">Pending Reviews</p>
                            <p class="mt-1 text-3xl font-extrabold text-slate-900">{{ $pendingReviews }}</p>
                            <p class="mt-2 text-xs text-slate-500">Manual grading</p>
                        </div>
                        <div class="w-11 h-11 rounded-2xl grid place-items-center" style="background:rgba(237,183,10,.16);">
                            <span class="w-2.5 h-2.5 rounded-full" style="background:{{ $cmYellow }};"></span>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm hover:shadow-md transition p-5">
                    <p class="text-sm font-semibold text-slate-600">Reports</p>
                    <p class="mt-1 text-base font-bold text-slate-900">Export</p>
                    <p class="mt-2 text-xs text-slate-500">Attendance + quiz summaries</p>

                    <div class="mt-4 flex flex-wrap gap-2">
                        <button type="button"
                                class="px-3 py-2 rounded-xl font-semibold text-white shadow-sm hover:shadow-md transition"
                                style="background:{{ $cmBlue }};">
                            Export CSV
                        </button>

                        <button type="button"
                                class="px-3 py-2 rounded-xl font-semibold border border-slate-200 bg-white
                                       text-slate-900 shadow-sm hover:shadow-md transition">
                            History
                        </button>
                    </div>
                </div>
            </div>

            {{-- Main grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-5">

                {{-- Attendance --}}
                <div class="lg:col-span-2 rounded-2xl border border-slate-200 bg-white shadow-sm p-5 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <h2 class="text-lg font-extrabold text-slate-900">Attendance</h2>
                            <p class="text-sm text-slate-600">Start a session and track check-ins.</p>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <button type="button"
                                    class="px-4 py-2 rounded-xl font-semibold shadow-sm hover:shadow-md transition"
                                    style="background:{{ $cmGreen }}; color:#0B1B0F;">
                                + New Session
                            </button>

                            <a href="{{ url('/attendance') }}"
                               class="px-4 py-2 rounded-xl font-semibold border border-slate-200 bg-white
                                      text-slate-900 shadow-sm hover:shadow-md transition">
                                Open
                            </a>
                        </div>
                    </div>

                    <div class="mt-5 rounded-2xl border border-slate-200 bg-slate-50 p-5">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">
                                    Current Session Code
                                </p>
                                <p class="mt-2 text-2xl font-extrabold tracking-[0.25em] text-slate-900">
                                    {{ $activeAttendance?->session_code ?? '— — — —' }}
                                </p>
                                <p class="mt-2 text-sm text-slate-600">
                                    Share code or show QR from Attendance page.
                                </p>
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="px-4 py-3 rounded-2xl border border-slate-200 bg-white">
                                    <p class="text-xs font-bold text-slate-500">Live Check-ins</p>
                                    <p class="mt-1 text-xl font-extrabold text-slate-900">
                                        {{ $liveCheckins ?? 0 }}
                                    </p>
                                </div>

                                <div class="w-12 h-12 rounded-2xl grid place-items-center" style="background:rgba(139,222,99,.18);">
                                    <span class="w-3 h-3 rounded-full" style="background:{{ $cmGreen }};"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Quizzes --}}
                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5 sm:p-6">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h2 class="text-lg font-extrabold text-slate-900">Quizzes</h2>
                            <p class="text-sm text-slate-600">Manage quizzes and results.</p>
                        </div>
                        <div class="w-11 h-11 rounded-2xl grid place-items-center" style="background:rgba(237,183,10,.16);">
                            <span class="w-2.5 h-2.5 rounded-full" style="background:{{ $cmYellow }};"></span>
                        </div>
                    </div>

                    <div class="mt-4 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Active Quiz</p>
                        <p class="mt-2 text-base font-extrabold text-slate-900">
                            {{ $activeQuiz?->title ?? 'None running' }}
                        </p>

                        <div class="mt-4 flex flex-wrap gap-2">
                            <a href="{{ url('/quizzes') }}"
                               class="px-4 py-2 rounded-xl font-semibold text-white shadow-sm hover:shadow-md transition"
                               style="background:{{ $cmBlue }};">
                                Open
                            </a>

                            <button type="button"
                                    class="px-4 py-2 rounded-xl font-semibold border border-slate-200 bg-white
                                           text-slate-900 shadow-sm hover:shadow-md transition">
                                Results
                            </button>
                        </div>
                    </div>

                    <div class="mt-4 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-sm font-bold text-slate-900">Performance Snapshot</p>
                        <div class="mt-3 h-24 rounded-xl border border-dashed border-slate-300 grid place-items-center">
                            <span class="text-xs text-slate-500">Chart placeholder</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection
