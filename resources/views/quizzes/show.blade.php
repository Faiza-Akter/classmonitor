@extends('layouts.app')

@section('content')
@php
  $cmBlue   = '#2463EB';
  $cmGreen  = '#8BDE63';
  $cmYellow = '#EDB70A';
  $cmRed    = '#EF4444';
@endphp

{{-- ✅ Same student pages wrapper: solid cmGreen + navbar border strip --}}
<div class="min-h-[calc(100vh-88px)]" style="background: {{ $cmGreen }};">

  {{-- ✅ Navbar bottom border strip --}}
  <div class="h-[6px] w-full"
       style="background: linear-gradient(90deg, {{ $cmBlue }}, {{ $cmGreen }}, {{ $cmYellow }});"></div>

  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-10">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
      <div class="text-center sm:text-left">
        <p class="text-xs font-extrabold tracking-widest uppercase text-white/90">Attempt Details</p>
        <h1 class="mt-2 text-3xl sm:text-4xl font-extrabold text-white">
          {{ $attempt->quiz?->title ?? 'Quiz' }}
        </h1>
        <p class="mt-2 text-sm sm:text-base text-white font-extrabold">
          Teacher:
          <span class="font-extrabold text-white">
            {{ $attempt->quiz?->teacher?->name ?? 'Teacher' }}
          </span>
        </p>
      </div>

      <div class="flex items-center justify-center sm:justify-end gap-2 flex-wrap">
        {{-- ✅ Back button: cmYellow outline + text cmYellow, height matches Dashboard --}}
        <a href="{{ route('student.quizzes.history') }}"
           class="cm-btn-outline-yellow">
          ← Back
        </a>

        <a href="{{ route('student.dashboard') }}"
           class="cm-btn-solid"
           style="--btn: {{ $cmBlue }}; --btn-text: #fff;">
          Dashboard
        </a>
      </div>
    </div>

    {{-- Score card --}}
    <section class="mt-6 cm-card">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <div class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-extrabold border"
               style="border-color: rgba(36,99,235,.20); background: rgba(36,99,235,.10); color: rgb(15 23 42);">
            <span class="w-2 h-2 rounded-full" style="background: {{ $cmBlue }};"></span>
            Score
          </div>

          <p class="mt-3 text-4xl sm:text-5xl font-extrabold leading-none tabular-nums" style="color: {{ $cmBlue }};">
            {{ (int)$attempt->score }}
            <span class="text-base sm:text-lg font-extrabold text-slate-600">/ {{ (int)$maxScore }}</span>
          </p>
        </div>

     
        <div class="">
          <p class="text-xs font-extrabold text-white text-slate-700 uppercase tracking-wider">Submitted</p>
          <p class="mt-1 text-sm font-extrabold text-white text-slate-900">
            {{ optional($attempt->submitted_at)?->timezone('Asia/Dhaka')->format('Y-m-d h:i A') ?? '—' }}
          </p>
        </div>
      </div>
    </section>

    {{-- Answers (NO inner scrollbar) --}}
    <section class="mt-6 cm-answers">
      <div class="space-y-4">
        @foreach($attempt->answers as $ans)
          @php
            $q = $ans->question;
            $type = $q->type ?? 'mcq';
            $correctOpt = $correctByQuestion[$q->id] ?? null;

            $label = 'Wrong';
            $badgeBg = 'rgba(239,68,68,.16)'; $badgeFg = '#7f1d1d';

            if ($type === 'short' && $ans->is_correct === null) {
              $label = 'Pending Review';
              $badgeBg = 'rgba(237,183,10,.20)'; $badgeFg = '#3a2b00';
            } elseif ($ans->is_correct === true) {
              $label = 'Correct';
              $badgeBg = 'rgba(139,222,99,.26)'; $badgeFg = '#0B1B0F';
            }

            $pointsEarned = '0';
            if ($type === 'short' && $ans->is_correct === null) {
              $pointsEarned = '—';
            } elseif ($ans->is_correct === true) {
              $pointsEarned = (string)($q->points ?? 0);
            }
          @endphp

          <article class="cm-qcard">
            <div class="flex items-start justify-between gap-4">
              <div class="min-w-0">
                <p class="text-base sm:text-lg font-extrabold text-slate-900 break-words">
                  {{ $q->text }}
                </p>

                <p class="mt-2 text-sm text-slate-600">
                  Points:
                  <span class="font-extrabold text-slate-900">{{ $q->points }}</span>
                  • Earned:
                  <span class="font-extrabold text-slate-900">{{ $pointsEarned }}</span>
                </p>
              </div>

              <span class="shrink-0 text-xs font-extrabold px-3 py-1.5 rounded-full border"
                    style="background:{{ $badgeBg }}; color:{{ $badgeFg }}; border-color: rgba(15,23,42,.10);">
                {{ $label }}
              </span>
            </div>

            @if(in_array($type, ['mcq','tf']))
              <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="cm-pill" style="border-color: rgba(36,99,235,.18); background: rgba(36,99,235,.06);">
                  <p class="text-xs font-extrabold text-slate-600">Your answer</p>
                  <p class="mt-1 text-sm font-extrabold text-slate-900 break-words">
                    {{ optional($ans->selectedOption)->text ?? '—' }}
                  </p>
                </div>

                <div class="cm-pill" style="border-color: rgba(139,222,99,.25); background: rgba(139,222,99,.10);">
                  <p class="text-xs font-extrabold text-slate-600">Correct answer</p>
                  <p class="mt-1 text-sm font-extrabold text-slate-900 break-words">
                    {{ $correctOpt?->text ?? '—' }}
                  </p>
                </div>
              </div>
            @else
              <div class="mt-4">
                <p class="text-sm font-extrabold text-slate-700">Your answer</p>

                {{-- ✅ Fix overflow: wrap long strings inside the card --}}
                <div class="mt-2 cm-answer-box rounded-2xl border p-4 text-slate-800"
                     style="border-color: rgba(226,232,240,1); background: rgba(248,250,252,1);">
                  {{ $ans->short_answer ?? '—' }}
                </div>

                <p class="mt-2 text-xs font-semibold text-slate-500">
                  Short answers are graded by the teacher.
                </p>
              </div>
            @endif
          </article>
        @endforeach
      </div>
    </section>

    {{-- Bottom actions --}}
    <div class="mt-6 flex flex-wrap gap-2 justify-center sm:justify-start">
      {{-- ✅ Back to Quiz History: cmYellow outline + text cmYellow --}}
      <a href="{{ route('student.quizzes.history') }}"
         class="cm-btn-outline-yellow">
        Back to Quiz History
      </a>

      <a href="{{ route('student.dashboard') }}"
         class="cm-btn-solid"
         style="--btn: {{ $cmBlue }}; --btn-text: #fff;">
        Dashboard
      </a>
    </div>

  </div>

  <style>
    /* main glass card style */
    .cm-card{
      background: rgba(36,99,235,.14);
      border: 1px solid rgba(255,255,255,.55);
      border-radius: 1.5rem;
      padding: 1.5rem;
      color: rgb(15 23 42);
      box-shadow: 0 18px 45px rgba(15,23,42,.14);
      overflow: hidden; /* extra safety */
    }

    /* ✅ Submitted: transparent/glass */
    .cm-mini{
      border-radius: 1.25rem;
      border: 1px solid rgba(255,255,255,.55);
      padding: 1rem 1.1rem;
      min-width: 260px;
      max-width: 100%;
      overflow: hidden;
    }


    /* ✅ Answers list (no scrollbar inside card) */
    .cm-answers{
      overflow: visible;
      padding-right: 0;
    }

    .cm-qcard{
      border: 1px solid rgba(226,232,240,1);
      border-radius: 1.5rem;
      background: rgba(255,255,255,.94);
      padding: 1.25rem;
      box-shadow: 0 10px 26px rgba(15,23,42,.06);
      overflow: hidden; /* ✅ contain long content */
    }

    .cm-pill{
      border-radius: 1.25rem;
      border: 1px solid rgba(226,232,240,1);
      background: rgba(255,255,255,.92);
      padding: .95rem 1rem;
      overflow: hidden;
    }
    .cm-pill :is(p){
      overflow-wrap: anywhere;
      word-break: break-word;
    }

    /* ✅ Fix long short answers (no overflow) */
    .cm-answer-box{
      max-width: 100%;
      overflow-x: hidden;
      overflow-wrap: anywhere;    /* breaks long continuous strings */
      word-break: break-word;     /* fallback */
      white-space: pre-wrap;      /* keep newlines, wrap long text */
    }

    /* buttons */
    .cm-btn-solid{
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: .62rem 1.05rem;   /* ✅ same height as outline */
      border-radius: 9999px;
      font-weight: 950;
      background: var(--btn);
      color: var(--btn-text, #fff);
      box-shadow: 0 10px 26px rgba(15,23,42,.12);
      transition: transform .15s ease, box-shadow .2s ease;
      white-space: nowrap;
    }
    .cm-btn-solid:hover{
      transform: translateY(-1px);
      box-shadow: 0 16px 34px rgba(15,23,42,.16);
    }

    /* ✅ Yellow outline button (Back + Back to Quiz History) */
    .cm-btn-outline-yellow{
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: .62rem 1.05rem;   /* ✅ height matches dashboard */
      border-radius: 9999px;
      border: 3px solid {{ $cmYellow }};
      color: {{ $cmYellow }} !important;
      font-weight: 950;
      background: #FFFFFF;
      backdrop-filter: blur(8px);
      -webkit-backdrop-filter: blur(8px);
      box-shadow: 0 10px 26px rgba(15,23,42,.10);
      transition: transform .15s ease, box-shadow .2s ease, background .2s ease;
      white-space: nowrap;
    }
    .cm-btn-outline-yellow:hover{
      transform: translateY(-1px);
      background: rgba(255, 255, 255, 1);
      box-shadow: 0 16px 34px rgba(15,23,42,.14);
    }

    /* ✅ global safety: prevent horizontal scroll due to long strings */
    html, body { overflow-x: hidden; }

    @media (prefers-reduced-motion: reduce){
      .cm-btn-solid, .cm-btn-outline-yellow{ transition:none !important; }
      .cm-btn-solid:hover, .cm-btn-outline-yellow:hover{ transform:none !important; }
    }
  </style>

</div>
@endsection
