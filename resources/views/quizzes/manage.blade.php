@extends('layouts.app')

@section('content')
@php
  $cmBlue='#2463EB'; $cmGreen='#8BDE63'; $cmYellow='#EDB70A';
@endphp

<div class="min-h-[calc(100vh-88px)] text-slate-900"
     style="background: linear-gradient(135deg, rgba(36,99,235,.08) 0%, #ffffff 55%, rgba(139,222,99,.10) 100%);">

    {{-- Top accent strip --}}
    <div class="h-[6px] w-full"
         style="background: linear-gradient(90deg, {{ $cmBlue }}, {{ $cmGreen }}, {{ $cmYellow }});"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">

        {{-- Header --}}
        <section class="cm-animate-in rounded-3xl overflow-hidden shadow-[0_18px_45px_rgba(15,23,42,0.12)]"
                 style="background: linear-gradient(110deg, rgba(157,183,255,.9) 0%, rgba(189,240,178,.9) 48%, rgba(255,224,138,.9) 100%);">
            {{-- ✅ reduce header height: p-5 sm:p-7 -> p-4 sm:p-5 --}}
            <div class="p-4 sm:p-5">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="min-w-0">
                        <p class="text-xs font-extrabold tracking-widest uppercase text-slate-800">Quiz</p>
                        {{-- ✅ slightly tighter spacing --}}
                        <h1 class="mt-1 text-2xl sm:text-3xl font-extrabold text-slate-900 truncate">
                            {{ $quiz->title }}
                        </h1>
                        <p class="mt-1 text-sm sm:text-base text-slate-800">
                            Add MCQ questions + options. Short-answer supported (manual grading).
                        </p>
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-wrap gap-2 justify-start lg:justify-end">
                        {{-- ✅ reduce size + better combo via new header button classes --}}
                        <a href="{{ route('quizzes.index') }}"
                           class="cm-btn-head-outline"
                           style="--btn: {{ $cmGreen }}; --btn-text: {{ $cmGreen }}; --btn-bg:#FFFFFF;">
                            Back
                        </a>

                        <a href="{{ route('quizzes.leaderboard', $quiz) }}"
                           class="cm-btn-head-solid"
                           style="--btn: {{ $cmBlue }}; --btn-text:#FFFFFF;">
                            Leaderboard
                        </a>

                        <a href="{{ route('quizzes.grading.index', $quiz) }}"
                           class="cm-btn-head-outline"
                           style="--btn: {{ $cmYellow }}; --btn-text: {{ $cmYellow }}; --btn-bg:#FFFFFF;">
                            Manual Grading
                        </a>

                        @if($quiz->status !== 'active')
                            <form method="POST" action="{{ route('quizzes.start', $quiz) }}">
                                @csrf
                                <button class="cm-btn-head-solid"
                                        style="--btn: {{ $cmGreen }}; --btn-text:#FFFFFF;">
                                    Start Quiz
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('quizzes.stop', $quiz) }}">
                                @csrf
                                <button class="cm-btn-head-solid"
                                        style="--btn: {{ $cmRed }}; --btn-text:#FFFFFF;">
                                    Stop Quiz
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        {{-- Alerts --}}
        @if(session('success'))
            <div class="mt-5 rounded-2xl border px-5 py-4 cm-reveal"
                 style="border-color: rgba(139,222,99,.35); background: rgba(139,222,99,.18);">
                <p class="font-extrabold text-green-900">{{ session('success') }}</p>
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

        <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-5 items-start">

            {{-- Add question --}}
            <section class="cm-panel cm-animate-in">
                {{-- ✅ add color to the marked header area --}}
                <div class="cm-panel-head cm-panel-head-blue">
                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900">Add Question</h2>
                        <p class="text-sm text-slate-600 mt-1">MCQ (auto-score) or Short (manual grade).</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-lg text-[11px] font-extrabold tracking-wider"
                          style="background:rgba(139,222,99,.12); color:#0b1b5a;">
                        NEW
                    </span>
                </div>

                <div class="cm-panel-body">
                    <form method="POST" action="{{ route('quizzes.questions.store', $quiz) }}" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-sm font-extrabold text-slate-800">Type</label>
                            <select name="type" class="cm-input mt-2">
                                <option value="mcq" selected>MCQ</option>
                                <option value="short">Short Answer</option>
                            </select>
                            <p class="text-xs text-slate-500 mt-2 font-semibold">
                                Short answers require manual grading.
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-extrabold text-slate-800">Question</label>
                            <textarea name="text" rows="3" required
                                      class="cm-input mt-2"
                                      placeholder="Write the question...">{{ old('text') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-extrabold text-slate-800">Points</label>
                            <input type="number" name="points" min="1" max="100" value="{{ old('points', 1) }}" required
                                   class="cm-input mt-2">
                        </div>

                        {{-- MCQ options --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @for($i=0; $i<4; $i++)
                                <div>
                                    <label class="block text-sm font-extrabold text-slate-800">Option {{ $i+1 }} (MCQ)</label>
                                    <input name="options[]" value="{{ old("options.$i") }}"
                                           class="cm-input mt-2"
                                           placeholder="Option text">
                                </div>
                            @endfor
                        </div>

                        <div>
                            <label class="block text-sm font-extrabold text-slate-800">Correct Option (MCQ)</label>
                            <select name="correct_index" class="cm-input mt-2">
                                <option value="0">Option 1</option>
                                <option value="1">Option 2</option>
                                <option value="2">Option 3</option>
                                <option value="3">Option 4</option>
                            </select>
                        </div>

                        <button type="submit"
                                class="cm-btn-solid w-full sm:w-auto"
                                style="--btn: {{ $cmYellow }}; --btn-text:#FFFFFF;">
                            Add Question
                        </button>
                    </form>
                </div>
            </section>

            {{-- Existing questions --}}
            <section class="cm-panel cm-animate-in">
                {{-- ✅ add color to the marked header area --}}
                <div class="cm-panel-head cm-panel-head-yellow">
                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900">Questions</h2>
                        <p class="text-sm text-slate-600 mt-1">Total: <span class="font-extrabold">{{ $quiz->questions->count() }}</span></p>
                    </div>
                    <span class="px-2.5 py-1 rounded-lg text-[11px] font-extrabold tracking-wider"
                          style="background: rgba(237,183,10,.22); color:#3a2b00;">
                        LIST
                    </span>
                </div>

                <div class="cm-panel-body">
                    <div class="space-y-4">
                        @forelse($quiz->questions as $q)
                            {{-- ✅ reduce question card height: p-4 -> p-3 --}}
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-3">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="pr-3 min-w-0">
                                        <p class="font-extrabold text-slate-900">{{ $q->text }}</p>
                                        <p class="text-sm text-slate-600 mt-1">Points: <span class="font-extrabold text-slate-800">{{ $q->points }}</span></p>
                                    </div>

                                    <div class="flex items-center gap-2 shrink-0 flex-wrap justify-end">
                                        <span class="text-xs font-extrabold px-2 py-1 rounded-lg tracking-wider"
                                              style="background: rgba(237,183,10,.18); color:#3a2b00;">
                                            {{ strtoupper($q->type ?? 'MCQ') }}
                                        </span>

                                        {{-- ✅ edit button: smaller + solid green bg --}}
                                        <a href="{{ route('quizzes.questions.edit', [$quiz, $q]) }}"
                                           class="cm-btn-mini-solid"
                                           style="--btn: {{ $cmGreen }}; --btn-text:#FFFFFF;">
                                            Edit
                                        </a>

                                        {{-- ✅ delete button: smaller + solid red bg --}}
                                        <form method="POST" action="{{ route('quizzes.questions.destroy', [$quiz, $q]) }}"
                                              onsubmit="return confirm('Delete this question?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="cm-btn-mini-solid"
                                                    style="--btn: #EF4444; --btn-text:#FFFFFF;">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                @if(($q->type ?? 'mcq') === 'mcq')
                                    <ul class="mt-3 space-y-1">
                                        @foreach($q->options as $opt)
                                            <li class="text-sm flex items-center gap-2">
                                                <span class="inline-block w-2 h-2 rounded-full"
                                                      style="background: {{ $opt->is_correct ? $cmGreen : '#cbd5e1' }};"></span>
                                                <span class="{{ $opt->is_correct ? 'font-semibold text-slate-900' : 'text-slate-700' }}">
                                                    {{ $opt->text }}
                                                </span>
                                                @if($opt->is_correct)
                                                    <span class="text-xs font-extrabold" style="color: {{ $cmGreen }};">(correct)</span>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="mt-3 text-sm text-slate-600 font-semibold">
                                        Short answer question (manual grading).
                                    </p>
                                @endif
                            </div>
                        @empty
                            <div class="text-slate-600 font-semibold">No questions yet.</div>
                        @endforelse
                    </div>
                </div>
            </section>

        </div>

        {{-- Styles --}}
        <style>
            .cm-animate-in{
                opacity:0;
                transform: translateY(10px);
                animation: cmIn .55s ease forwards;
            }
            @keyframes cmIn{ to{ opacity:1; transform:none; } }

            .cm-reveal{ opacity:0; transform: translateY(14px); }
            .cm-reveal.cm-in{
                opacity:1; transform:none;
                transition: opacity .55s ease, transform .55s ease;
            }

            .cm-panel{
                border: 1px solid rgba(226,232,240,1);
                border-radius: 1.5rem;
                background:#fff;
                box-shadow: 0 10px 30px rgba(15,23,42,.06);
                overflow:hidden;
            }

            /* ✅ colored headers for the two panels (only adds bg color) */
            .cm-panel-head{
                padding: 1.25rem;
                border-bottom: 1px solid rgba(226,232,240,1);
                display:flex;
                align-items:flex-start;
                justify-content:space-between;
                gap:1rem;
            }
            .cm-panel-head-blue{
                background: rgba(36, 99, 235, 0.17);
            }
            .cm-panel-head-yellow{
                background: rgba(237, 184, 10, 0.15);
            }

            .cm-panel-body{ padding: 1.25rem; }
            @media (min-width: 640px){
                .cm-panel-head{ padding: 1.35rem; } /* tiny reduction in height, keeps layout */
                .cm-panel-body{ padding: 1.5rem; }
            }

            .cm-input{
                width:100%;
                border-radius: 1rem;
                border: 1px solid rgba(226,232,240,1);
                padding: .85rem 1rem;
                font-weight: 600;
                color: rgb(15 23 42);
                background: rgba(255,255,255,.95);
                transition: box-shadow .2s ease, border-color .2s ease, transform .15s ease;
                outline: none;
            }
            .cm-input::placeholder{ color: rgba(100,116,139,.75); font-weight:600; }
            .cm-input:focus{
                border-color: rgba(36,99,235,.55);
                box-shadow: 0 0 0 4px rgba(36,99,235,.16);
                transform: translateY(-1px);
            }

            .cm-btn-solid{
                display:inline-flex;
                align-items:center;
                justify-content:center;
                padding: .72rem 1.1rem;
                border-radius: 9999px;
                font-weight: 950;
                background: var(--btn);
                color: var(--btn-text, #fff);
                box-shadow: 0 6px 16px rgba(15,23,42,.10);
                transition: transform .15s ease, box-shadow .2s ease, filter .2s ease;
                white-space: nowrap;
            }
            .cm-btn-solid:hover{
                transform: translateY(-1px);
                box-shadow: 0 12px 26px rgba(15,23,42,.14);
                filter: brightness(1.02);
            }

            .cm-btn-outline{
                display:inline-flex;
                align-items:center;
                justify-content:center;
                padding: .72rem 1.1rem;
                border-radius: 9999px;
                border: 2px solid var(--btn);
                color: var(--btn-text, var(--btn));
                font-weight: 950;
                background: var(--btn-bg, #fff);
                box-shadow: 0 6px 16px rgba(15,23,42,.08);
                transition: transform .15s ease, box-shadow .2s ease, background .2s ease;
                white-space: nowrap;
            }
            .cm-btn-outline:hover{
                transform: translateY(-1px);
                box-shadow: 0 12px 26px rgba(15,23,42,.12);
                background: rgba(36,99,235,.03);
            }

            /* ✅ smaller header buttons (only used in header) */
            .cm-btn-head-solid,
            .cm-btn-head-outline{
                display:inline-flex;
                align-items:center;
                justify-content:center;
                padding: .45rem .9rem;
                border-radius: 9999px;
                font-weight: 950;
                font-size: .875rem;
                line-height: 1.1;
                white-space: nowrap;
            }
            .cm-btn-head-solid{
                background: var(--btn);
                color: var(--btn-text, #fff);
                box-shadow: 0 6px 14px rgba(15,23,42,.10);
                transition: transform .15s ease, box-shadow .2s ease, filter .2s ease;
            }
            .cm-btn-head-solid:hover{
                transform: translateY(-1px);
                box-shadow: 0 12px 24px rgba(15,23,42,.14);
                filter: brightness(1.02);
            }
            .cm-btn-head-outline{
                border: 2px solid var(--btn);
                color: var(--btn-text, var(--btn));
                background: var(--btn-bg, #fff);
                box-shadow: 0 6px 14px rgba(15,23,42,.08);
                transition: transform .15s ease, box-shadow .2s ease, background .2s ease;
            }
            .cm-btn-head-outline:hover{
                transform: translateY(-1px);
                box-shadow: 0 12px 24px rgba(15,23,42,.12);
                background: rgba(255, 255, 255, 1);
            }

            /* ✅ smaller solid buttons for Edit/Delete in question card */
            .cm-btn-mini-solid{
                display:inline-flex;
                align-items:center;
                justify-content:center;
                padding: .42rem .85rem;
                border-radius: 9999px;
                font-weight: 950;
                font-size: .875rem;
                background: var(--btn);
                color: var(--btn-text, #fff);
                box-shadow: 0 6px 14px rgba(15,23,42,.10);
                transition: transform .15s ease, box-shadow .2s ease, filter .2s ease;
                white-space: nowrap;
            }
            .cm-btn-mini-solid:hover{
                transform: translateY(-1px);
                box-shadow: 0 12px 24px rgba(15,23,42,.14);
                filter: brightness(1.02);
            }

            @media (prefers-reduced-motion: reduce){
                .cm-animate-in{ animation:none !important; opacity:1 !important; transform:none !important; }
                .cm-reveal, .cm-reveal.cm-in{ transition:none !important; opacity:1 !important; transform:none !important; }
                .cm-btn-solid, .cm-btn-outline, .cm-input,
                .cm-btn-head-solid, .cm-btn-head-outline, .cm-btn-mini-solid{ transition:none !important; }
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
