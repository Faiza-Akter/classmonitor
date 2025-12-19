@extends('layouts.app')

@section('content')
@php
    $cmGreen  = '#8BDE63';
    $cmBlue   = '#2463EB';
    $cmYellow = '#EDB70A';
    $cmRed    = '#EF4444';
@endphp

<div class="min-h-[calc(100vh-88px)] text-slate-900 relative overflow-x-hidden" style="background:#8BDE63;">
    {{-- Top accent strip --}}
    <div class="h-[6px] w-full"
         style="background: linear-gradient(90deg, {{ $cmBlue }}, {{ $cmGreen }}, {{ $cmYellow }});"></div>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-10 overflow-x-hidden">

        {{-- Header (NO card) --}}
        <div class="cm-animate-in">
            <p class="text-xs font-bold tracking-widest uppercase text-white/80">Student • Result</p>
            <h1 class="mt-2 text-2xl sm:text-3xl lg:text-4xl font-extrabold leading-tight text-white">
                {{ $quiz->title }}
            </h1>
            <p class="mt-2 text-sm sm:text-base text-white/85">
                Review your score and answers.
            </p>
        </div>

        {{-- Score panel --}}
        <section class="mt-6 lg:mt-8 cm-panel cm-reveal" style="border-color: rgba(36,99,235,.18);">
            <div class="cm-panel-head" style="background: rgba(36,99,235,.08);">
                <div>
                    <h2 class="text-lg sm:text-xl font-extrabold text-slate-900">Your Score</h2>
                    <p class="mt-1 text-sm text-slate-600">Submission summary</p>
                </div>

                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-extrabold border bg-white"
                      style="border-color: rgba(36,99,235,.18); color: {{ $cmBlue }};">
                    <span class="w-2 h-2 rounded-full" style="background: {{ $cmBlue }};"></span>
                    Result
                </span>
            </div>

            <div class="cm-panel-body">
                <p class="text-sm font-bold text-slate-600">Score</p>
                <p class="mt-1 text-4xl sm:text-5xl font-extrabold tabular-nums" style="color: {{ $cmBlue }};">
                    {{ (int)($attempt->score ?? 0) }}
                    @isset($maxScore)
                        <span class="text-base sm:text-lg font-extrabold text-slate-500">/ {{ $maxScore }}</span>
                    @endisset
                </p>

                <div class="mt-4 rounded-2xl border p-4"
                     style="border-color: rgba(226,232,240,1); background: rgba(15,23,42,.03);">
                    <p class="text-xs font-bold tracking-widest uppercase text-slate-500">Submitted at</p>
                    <p class="mt-1 text-sm font-extrabold text-slate-900">
                        {{ optional($attempt->submitted_at)?->timezone('Asia/Dhaka')->format('Y-m-d h:i A') ?? '—' }}
                    </p>
                </div>

                <p class="mt-3 text-xs text-slate-500">
                    Note: Short-answer questions may show <span class="font-bold">Pending Review</span> until your teacher grades them.
                </p>
            </div>
        </section>

        {{-- Answers list --}}
        <div class="mt-6 lg:mt-8 space-y-5">
            @foreach($attempt->answers as $ans)
                @php
                    $q = $ans->question;
                    $type = $q->type ?? 'mcq';

                    // status badge
                    $label = 'Wrong';
                    $bg = 'rgba(239,68,68,.15)'; $fg = '#7f1d1d';

                    if ($type === 'short' && $ans->is_correct === null) {
                        $label = 'Pending Review';
                        $bg = 'rgba(237,183,10,.18)'; $fg = '#3a2b00';
                    } elseif ($ans->is_correct === true) {
                        $label = 'Correct';
                        $bg = 'rgba(139,222,99,.22)'; $fg = '#0B1B0F';
                    }

                    // panel accent by status
                    $border = 'rgba(226,232,240,1)';
                    $headBg = 'rgba(15,23,42,.03)';
                    if ($label === 'Correct') { $border = 'rgba(139,222,99,.28)'; $headBg = 'rgba(139,222,99,.10)'; }
                    if ($label === 'Wrong') { $border = 'rgba(239,68,68,.22)'; $headBg = 'rgba(239,68,68,.08)'; }
                    if ($label === 'Pending Review') { $border = 'rgba(237,183,10,.26)'; $headBg = 'rgba(237,183,10,.10)'; }
                @endphp

                <section class="cm-panel cm-reveal" style="border-color: {{ $border }};">
                    <div class="cm-panel-head" style="background: {{ $headBg }};">
                        <div class="min-w-0">
                            <p class="font-extrabold text-slate-900 break-words">{{ $q->text }}</p>
                            <p class="mt-1 text-sm text-slate-600">
                                Points: <span class="font-extrabold text-slate-900">{{ $q->points }}</span>
                            </p>
                        </div>

                        <span class="shrink-0 inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold border bg-white"
                              style="border-color: rgba(15,23,42,.10); background: {{ $bg }}; color: {{ $fg }};">
                            {{ $label }}
                        </span>
                    </div>

                    <div class="cm-panel-body">
                        {{-- MCQ / TF --}}
                        @if(in_array($type, ['mcq','tf']))
                            <div class="rounded-2xl border p-4"
                                 style="border-color: rgba(226,232,240,1); background: rgba(15,23,42,.03);">
                                <p class="text-xs font-bold tracking-widest uppercase text-slate-500">Selected</p>
                                <p class="mt-1 text-sm font-extrabold text-slate-900">
                                    {{ optional($ans->selectedOption)->text ?? '—' }}
                                </p>
                            </div>

                        {{-- SHORT ANSWER --}}
                        @else
                            <div class="rounded-2xl border p-4"
                                 style="border-color: rgba(226,232,240,1); background: rgba(15,23,42,.03);">
                                <p class="text-xs font-bold tracking-widest uppercase text-slate-500">Your answer</p>
                                <p class="mt-2 text-sm text-slate-800 whitespace-pre-wrap">
                                    {{ $ans->short_answer ?? '—' }}
                                </p>
                            </div>

                            <p class="mt-2 text-xs text-slate-500">
                                This question will be graded by the teacher.
                            </p>
                        @endif
                    </div>
                </section>
            @endforeach
        </div>

        {{-- Back --}}
        <div class="mt-6 lg:mt-8 cm-reveal flex">
            <a href="{{ route('dashboard') }}"
               class="cm-btn-solid"
               style="--btn: {{ $cmBlue }}; --btn-text:#FFFFFF;">
                Back to Dashboard
            </a>
        </div>

        <div class="h-8"></div>

        {{-- Styles --}}
        <style>
            html, body { overflow-x: hidden; }

            .cm-animate-in{opacity:0;transform:translateY(10px);animation:cmIn .55s ease forwards;}
            @keyframes cmIn{to{opacity:1;transform:translateY(0);}}

            .cm-reveal{opacity:0;transform:translateY(14px);}
            .cm-reveal.cm-in{opacity:1;transform:translateY(0);transition:opacity .55s ease, transform .55s ease;}

            .cm-panel{
                border:1px solid rgba(226,232,240,1);
                background:#fff;
                border-radius:1.5rem;
                box-shadow:0 10px 30px rgba(15,23,42,.06);
                overflow:hidden;
            }
            .cm-panel-head{
                padding:1.15rem 1.25rem 1rem 1.25rem;
                border-bottom:1px solid rgba(226,232,240,1);
                display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;
            }
            .cm-panel-body{ padding:1.25rem; }

            .cm-btn-solid{
                display:inline-flex;align-items:center;justify-content:center;
                padding:.72rem 1.15rem;border-radius:9999px;border:2px solid transparent;
                background:var(--btn,#2463EB);color:var(--btn-text,#fff);
                font-weight:950;
                box-shadow:0 6px 16px rgba(15,23,42,.10);
                transition:transform .15s ease, box-shadow .2s ease, filter .2s ease;
                white-space:nowrap;
            }
            .cm-btn-solid:hover{
                transform:translateY(-1px);
                box-shadow:0 12px 26px rgba(15,23,42,.14);
                filter:saturate(1.06);
            }
        </style>

        {{-- JS: reveal animation --}}
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
