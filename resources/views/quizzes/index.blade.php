@extends('layouts.app')

@section('content')
    @php
        $cmBlue = '#2463EB';
        $cmGreen = '#8BDE63';
        $cmYellow = '#EDB70A';
        $cmRed = '#EF4444';
    @endphp

    <div class="min-h-[calc(100vh-88px)] text-white" style="
      background:
        radial-gradient(900px 520px at 18% 10%, rgba(255,255,255,.16) 0%, rgba(255,255,255,0) 60%),
        radial-gradient(760px 460px at 85% 26%, rgba(139,222,99,.22) 0%, rgba(255,255,255,0) 65%),
        radial-gradient(760px 520px at 70% 92%, rgba(237,183,10,.14) 0%, rgba(255,255,255,0) 60%),
        linear-gradient(135deg, rgba(36,99,235,.95) 0%, rgba(36,99,235,.88) 55%, rgba(29,78,216,.95) 100%);
    ">
        {{-- Top accent strip --}}
        <div class="h-[6px] w-full"
            style="background: linear-gradient(90deg, {{ $cmBlue }}, {{ $cmGreen }}, {{ $cmYellow }});"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-10">
            {{-- MAIN PAGE HEADER --}}
            <div class="mb-6 flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
                <div>
                    <p class="text-xs font-extrabold tracking-widest uppercase text-white/80">
                        Quizzes
                    </p>

                    <h1 class="mt-2 text-3xl sm:text-4xl font-extrabold text-white">
                        Your Quizzes
                    </h1>

                    <p class="mt-2 text-sm sm:text-base text-white/80 max-w-2xl">
                        Create quizzes, manage questions, and start or stop them during class.
                    </p>
                </div>

                {{-- Create Quiz button --}}
                <a href="{{ route('quizzes.create') }}" class="cm-btn-green">
                    + Create Quiz
                </a>
            </div>

            {{-- Alerts --}}
            @if(session('success'))
                <div class="mt-5 rounded-2xl border px-5 py-4 cm-reveal"
                    style="border-color: rgba(139,222,99,.35); background: rgba(139,222,99,.18);">
                    <p class="font-semibold text-white">{{ session('success') }}</p>
                </div>
            @endif

            @if($errors->any())
                <div class="mt-5 rounded-2xl border px-5 py-4 cm-reveal"
                    style="border-color: rgba(239,68,68,.40); background: rgba(239,68,68,.16);">
                    <ul class="list-disc pl-5 text-sm font-semibold text-white">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- QUIZ LIST --}}
            <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-5 items-stretch">
                @forelse($quizzes as $quiz)
                    @php
                        $badge = match ($quiz->status) {
                            'active' => ['bg' => 'rgba(139,222,99,.24)', 'text' => '#0B1B0F', 'label' => 'ACTIVE'],
                            'closed' => ['bg' => 'rgba(237,183,10,.24)', 'text' => '#3a2b00', 'label' => 'CLOSED'],
                            default => ['bg' => 'rgba(36,99,235,.14)', 'text' => '#0b1b5a', 'label' => 'DRAFT'],
                        };

                        // ✅ tinted card header background (soft), BUT text remains dark now
                        $headTint = match ($quiz->status) {
                            'active' => 'linear-gradient(110deg, rgba(139,222,99,.22) 0%, rgba(255,255,255,.94) 55%, rgba(237,183,10,.14) 100%)',
                            'closed' => 'linear-gradient(110deg, rgba(237,183,10,.18) 0%, rgba(255,255,255,.94) 55%, rgba(139,222,99,.12) 100%)',
                            default => 'linear-gradient(110deg, rgba(36,99,235,.10) 0%, rgba(255,255,255,.96) 55%, rgba(237,183,10,.12) 100%)',
                        };
                    @endphp

                    <section class="cm-panel cm-reveal flex flex-col">
                        {{-- ✅ QUIZ CARD HEADER: tinted background + DARK text (fix white issue) --}}
                        <div class="cm-panel-head cm-cardhead" style="background: {{ $headTint }};">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h2 class="text-lg font-extrabold truncate">{{ $quiz->title }}</h2>
                                    <span class="px-2.5 py-1 rounded-lg text-[11px] font-extrabold tracking-wider"
                                        style="background:{{ $badge['bg'] }}; color:{{ $badge['text'] }};">
                                        {{ $badge['label'] }}
                                    </span>
                                </div>

                                <p class="mt-2 text-sm cm-meta">
                                    Questions:
                                    <span class="font-extrabold text-slate-900">{{ $quiz->questions_count }}</span>
                                    @if($quiz->duration)
                                        • Duration:
                                        <span class="font-extrabold text-slate-900">{{ $quiz->duration }} min</span>
                                    @endif
                                </p>
                            </div>

                            {{-- ✅ Manage button back to BLUE outline (not white) --}}
                            <a href="{{ route('quizzes.manage', $quiz) }}" class="cm-manage-btn" aria-label="Manage quiz">
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

                                {{-- Student Play Link -> cmYellow --}}
                                <a href="{{ route('quizzes.play', $quiz) }}" class="cm-btn-solid"
                                    style="--btn: {{ $cmYellow }}; --btn-text:#1f1600;">
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
                .cm-reveal {
                    opacity: 0;
                    transform: translateY(14px);
                }

                .cm-reveal.cm-in {
                    opacity: 1;
                    transform: none;
                    transition: opacity .55s ease, transform .55s ease;
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

                /* ✅ header text should be dark */
                .cm-cardhead {
                    color: rgb(15 23 42);
                    /* slate-900 */
                    border-bottom-color: rgba(226, 232, 240, 1) !important;
                }

                .cm-cardhead .cm-meta {
                    color: rgba(15, 23, 42, .72);
                    font-weight: 600;
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
                    transition: transform .15s ease, box-shadow .2s ease;
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
                    transition: transform .15s ease, box-shadow .2s ease;
                    white-space: nowrap;
                }

                .cm-btn-outline:hover {
                    transform: translateY(-1px);
                    box-shadow: 0 12px 26px rgba(15, 23, 42, .12);
                }

                /* ✅ Manage button blue outline (original style) */
                .cm-manage-btn {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    gap: .45rem;
                    height: 44px;
                    min-width: 120px;
                    padding: 0 16px;
                    border-radius: 9999px;
                    border: 2px solid #2463EB;
                    color: #2463EB;
                    background: #ffffff;
                    font-weight: 950;
                    box-shadow: 0 6px 16px rgba(15, 23, 42, .08);
                    transition: transform .15s ease, box-shadow .2s ease;
                    white-space: nowrap;
                }

                .cm-manage-btn:hover {
                    transform: translateY(-1px);
                    box-shadow: 0 12px 26px rgba(15, 23, 42, .12);
                    background: #ffffff;
                }

                /* Create Quiz GREEN button */
                .cm-btn-green {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    padding: .70rem 1.10rem;
                    border-radius: 9999px;
                    font-weight: 950;
                    background:
                        {{ $cmGreen }}
                    ;
                    color: #0B1B0F;
                    border: 1px solid rgba(139, 222, 99, .55);
                    box-shadow: 0 14px 30px rgba(15, 23, 42, .16);
                    transition: transform .15s ease, box-shadow .2s ease;
                    white-space: nowrap;
                }

                .cm-btn-green:hover {
                    transform: translateY(-1px);
                    box-shadow: 0 18px 38px rgba(15, 23, 42, .20);
                }

                @media (prefers-reduced-motion: reduce) {

                    .cm-reveal,
                    .cm-reveal.cm-in {
                        transition: none !important;
                        opacity: 1 !important;
                        transform: none !important;
                    }

                    .cm-btn-solid,
                    .cm-btn-outline,
                    .cm-manage-btn,
                    .cm-btn-green {
                        transition: none !important;
                    }

                    .cm-btn-solid:hover,
                    .cm-btn-outline:hover,
                    .cm-manage-btn:hover,
                    .cm-btn-green:hover {
                        transform: none !important;
                    }
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