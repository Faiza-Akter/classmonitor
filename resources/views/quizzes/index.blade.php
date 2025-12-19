@extends('layouts.app')

@section('content')
    @php
        $cmBlue = '#2463EB';
        $cmGreen = '#8BDE63';
        $cmYellow = '#EDB70A';
        $cmRed = '#EF4444';
    @endphp

    <div class="min-h-[calc(100vh-88px)] text-slate-900" 
        style="background: linear-gradient(135deg, rgba(36,99,235,.10) 0%, #ffffff 55%, rgba(139,222,99,.10) 100%);">

        {{-- Top accent strip --}}
        <div class="h-[6px] w-full"
            style="background: linear-gradient(90deg, {{ $cmBlue }}, {{ $cmGreen }}, {{ $cmYellow }});"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">

            {{-- HERO HEADER – SOLID, STRONG GRADIENT CARD --}}
            <section class="cm-hero cm-animate-in rounded-3xl overflow-hidden shadow-[0_18px_45px_rgba(15,23,42,0.18)]"
                style="
            background: linear-gradient(
                110deg,
                #9DB7FF 0%,
                #BDF0B2 48%,
                #FFE08A 100%
            );
        ">
                <div class="p-4 sm:p-6">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                        <div>
                            <p class="text-xs font-bold tracking-widest uppercase text-slate-800">
                                QUIZZES
                            </p>

                            <h1 class="mt-2 text-3xl sm:text-4xl font-extrabold text-slate-900">
                                Your <span class="text-[#2463EB]">Quizzes</span>
                            </h1>

                            <p class="mt-3 text-base text-slate-800 max-w-2xl">
                                Create quizzes, manage questions, and start or stop them during class.
                            </p>
                        </div>

                        <a href="{{ route('quizzes.create') }}" class="cm-btn-solid"
                            style="--btn:#2463EB; --btn-text:#FFFFFF;">
                            + Create Quiz
                        </a>
                    </div>
                </div>
            </section>

            {{-- Alerts --}}
            @if(session('success'))
                <div class="mt-5 rounded-2xl border px-5 py-4 cm-reveal"
                    style="border-color: rgba(139,222,99,.35); background: rgba(139,222,99,.18);">
                    <p class="font-semibold text-green-900">{{ session('success') }}</p>
                </div>
            @endif

            @if($errors->any())
                <div class="mt-5 rounded-2xl border px-5 py-4 cm-reveal"
                    style="border-color: rgba(239,68,68,.35); background: rgba(239,68,68,.12);">
                    <ul class="list-disc pl-5 text-sm font-semibold text-red-900">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- QUIZ LIST --}}
            <div class="mt-6 lg:mt-8 grid grid-cols-1 lg:grid-cols-2 gap-5 items-stretch">
                @forelse($quizzes as $quiz)
                    @php
                        $badge = match ($quiz->status) {
                            'active' => ['bg' => 'rgba(139,222,99,.22)', 'text' => '#0B1B0F', 'label' => 'ACTIVE'],
                            'closed' => ['bg' => 'rgba(237,183,10,.22)', 'text' => '#3a2b00', 'label' => 'CLOSED'],
                            default => ['bg' => 'rgba(36,99,235,.14)', 'text' => '#0b1b5a', 'label' => 'DRAFT'],
                        };
                    @endphp

                    <section class="cm-panel cm-reveal flex flex-col">
                        <div class="cm-panel-head">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h2 class="text-lg font-extrabold truncate">{{ $quiz->title }}</h2>
                                    <span class="px-2.5 py-1 rounded-lg text-[11px] font-extrabold tracking-wider"
                                        style="background:{{ $badge['bg'] }}; color:{{ $badge['text'] }};">
                                        {{ $badge['label'] }}
                                    </span>
                                </div>

                                <p class="mt-2 text-sm text-slate-600">
                                    Questions:
                                    <span class="font-extrabold text-slate-900">{{ $quiz->questions_count }}</span>
                                    @if($quiz->duration)
                                        • Duration:
                                        <span class="font-extrabold text-slate-900">{{ $quiz->duration }} min</span>
                                    @endif
                                </p>
                            </div>

                            {{-- Improved Manage button: consistent height/width, icon, better padding --}}
                            <a href="{{ route('quizzes.manage', $quiz) }}" class="cm-manage-btn" style="--btn: {{ $cmBlue }};"
                                aria-label="Manage quiz">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="#2463EB">
                                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 20h9" />
                                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z" />
                                </svg>
                                <span>Manage</span>
                            </a>
                        </div>

                        <div class="cm-panel-body flex-1">
                            <div class="flex flex-wrap gap-2">
                                @if($quiz->status !== 'active')
                                    <form method="POST" action="{{ route('quizzes.start', $quiz) }}">
                                        @csrf
                                        <button class="cm-btn-solid" style="--btn: {{ $cmGreen }}; --btn-text:#0B1B0F;">
                                            Start
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('quizzes.stop', $quiz) }}">
                                        @csrf
                                        <button class="cm-btn-outline" style="--btn: {{ $cmRed }}; --btn-text: {{ $cmRed }};">
                                            Stop
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('quizzes.play', $quiz) }}" class="cm-btn-solid" style="--btn: {{ $cmBlue }};">
                                    Student Play Link
                                </a>
                            </div>
                        </div>
                    </section>
                @empty
                    <section class="cm-panel cm-reveal lg:col-span-2">
                        <div class="cm-panel-body text-center py-10 text-slate-600">
                            No quizzes yet. Click
                            <span class="font-extrabold">Create Quiz</span>
                            to get started.
                        </div>
                    </section>
                @endforelse
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
                        transform: none;
                    }
                }

                .cm-reveal {
                    opacity: 0;
                    transform: translateY(14px);
                }

                .cm-reveal.cm-in {
                    opacity: 1;
                    transform: none;
                    transition: opacity .55s ease, transform .55s ease;
                }

                @media (prefers-reduced-motion: reduce) {
                    .cm-animate-in {
                        animation: none !important;
                        opacity: 1 !important;
                        transform: none !important;
                    }

                    .cm-reveal,
                    .cm-reveal.cm-in {
                        transition: none !important;
                        opacity: 1 !important;
                        transform: none !important;
                    }

                    .cm-btn-solid,
                    .cm-btn-outline,
                    .cm-manage-btn {
                        transition: none !important;
                    }

                    .cm-btn-solid:hover,
                    .cm-btn-outline:hover,
                    .cm-manage-btn:hover {
                        transform: none !important;
                    }
                }

                .cm-panel {
                    border: 1px solid rgba(226, 232, 240, 1);
                    border-radius: 1.5rem;
                    background: #fff;
                    box-shadow: 0 10px 30px rgba(15, 23, 42, .06);
                    overflow: hidden;
                }

                .cm-panel-head {
                    padding: 1.25rem;
                    border-bottom: 1px solid rgba(226, 232, 240, 1);
                    display: flex;
                    align-items: flex-start;
                    justify-content: space-between;
                    gap: 1rem;
                }

                .cm-panel-body {
                    padding: 1.25rem;
                }

                .cm-btn-solid {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    padding: .62rem 1rem;
                    border-radius: 9999px;
                    font-weight: 950;
                    background: var(--btn);
                    color: var(--btn-text, #fff);
                    box-shadow: 0 6px 16px rgba(15, 23, 42, .10);
                    transition: transform .15s ease, box-shadow .2s ease, filter .2s ease;
                    white-space: nowrap;
                }

                .cm-btn-solid:hover {
                    transform: translateY(-1px);
                    box-shadow: 0 12px 26px rgba(15, 23, 42, .14);
                }

                .cm-btn-outline {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    padding: .62rem 1rem;
                    border-radius: 9999px;
                    border: 2px solid var(--btn);
                    color: var(--btn-text, var(--btn));
                    font-weight: 950;
                    background: #fff;
                    box-shadow: 0 6px 16px rgba(15, 23, 42, .08);
                    transition: transform .15s ease, box-shadow .2s ease, background .2s ease;
                    white-space: nowrap;
                }

                .cm-btn-outline:hover {
                    transform: translateY(-1px);
                    box-shadow: 0 12px 26px rgba(15, 23, 42, .12);
                }

                /* Manage button: fixed height, better width, icon + text, consistent with pills */
                .cm-manage-btn {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    gap: .45rem;
                    height: 44px;
                    min-width: 120px;
                    padding: 0 16px;
                    border-radius: 9999px;

                    /* SOLID blue outline */
                    border: 2px solid #2463EB;
                    color: #2463EB;
                    background: #ffffff;

                    font-weight: 950;
                    box-shadow: 0 6px 16px rgba(15, 23, 42, .08);
                    transition: transform .15s ease, box-shadow .2s ease, background .2s ease;
                    white-space: nowrap;
                }

                .cm-manage-btn:hover {
                    transform: translateY(-1px);
                    box-shadow: 0 12px 26px rgba(15, 23, 42, .12);
                    background: #ffffff;
                }
            </style>

            {{-- Reveal JS --}}
            <script>
                (function () {
                    const io = new IntersectionObserver(entries => {
                        entries.forEach(x => {
                            if (x.isIntersecting) {
                                x.target.classList.add('cm-in');
                                io.unobserve(x.target);
                            }
                        });
                    }, { threshold: .1 });
                    document.querySelectorAll('.cm-reveal').forEach(el => io.observe(el));
                })();
            </script>

        </div>
    </div>
@endsection