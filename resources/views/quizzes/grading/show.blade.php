@extends('layouts.app')

@section('content')
@php
  $cmBlue   = '#2463EB';
  $cmGreen  = '#8BDE63';
  $cmYellow = '#EDB70A';
  $cmRed    = '#EF4444';
@endphp

<div class="min-h-[calc(100vh-88px)] text-slate-900 relative overflow-x-hidden" style="background:#2463EB;">
  {{-- Top accent strip --}}
  <div class="h-[6px] w-full"
       style="background: linear-gradient(90deg, {{ $cmBlue }}, {{ $cmGreen }}, {{ $cmYellow }});"></div>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8 overflow-x-hidden">

    {{-- Header (NO card) --}}
    <div class="cm-animate-in">
      <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div class="min-w-0">
          <p class="text-xs font-bold tracking-widest uppercase text-white/80">Teacher • Grade Attempt</p>
          <h1 class="mt-2 text-2xl sm:text-3xl lg:text-4xl font-extrabold leading-tight text-white truncate">
            {{ $quiz->title }}
          </h1>

          <p class="mt-2 text-sm sm:text-base text-white/85">
            Student:
            <span class="font-extrabold text-white">{{ $attempt->student?->name ?? 'Student' }}</span>
            <span class="mx-2">•</span>
            Current score:
            <span class="font-extrabold text-white">{{ $attempt->score ?? 0 }}</span>
          </p>
        </div>

        <div class="flex flex-wrap gap-2 shrink-0">
          <a href="{{ route('quizzes.grading.index', $quiz) }}"
             class="cm-btn-solid"
             style="--btn: {{ $cmGreen }}; --btn-text:#FFFFFF;">
            Back
          </a>
        </div>
      </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
      <div class="mt-5 cm-alert cm-reveal"
           style="border-color: rgba(139,222,99,.35); background: rgba(139,222,99,.16); color:#0f172a;">
        <div class="flex items-start gap-3">
          <div class="cm-alert-dot" style="background: {{ $cmGreen }};"></div>
          <div class="min-w-0">
            <p class="font-extrabold">Saved</p>
            <p class="text-sm opacity-90">{{ session('success') }}</p>
          </div>
        </div>
      </div>
    @endif

    @if($errors->any())
      <div class="mt-5 cm-alert cm-reveal"
           style="border-color: rgba(239,68,68,.35); background: rgba(239,68,68,.14); color:#0f172a;">
        <div class="flex items-start gap-3">
          <div class="cm-alert-dot" style="background: {{ $cmRed }};"></div>
          <div class="min-w-0">
            <p class="font-extrabold">Please fix the following</p>
            <ul class="mt-2 list-disc pl-5 text-sm">
              @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
    @endif

    {{-- Grading form --}}
    <form class="mt-6 lg:mt-8 space-y-5"
          method="POST"
          action="{{ route('quizzes.grading.update', [$quiz, $attempt]) }}">
      @csrf
      @method('PATCH')

      {{-- Short answers only --}}
      @php $hasShort = false; @endphp

      @foreach($attempt->answers as $ans)
        @php $q = $ans->question; @endphp

        @if(($q?->type ?? '') === 'short')
          @php $hasShort = true; @endphp

          <section class="cm-panel cm-reveal" style="border-color: rgba(36,99,235,.18);">
            <div class="cm-panel-head" style="background: rgba(36,99,235,.08);">
              <div class="min-w-0">
                <p class="text-xs font-bold tracking-widest uppercase text-slate-500">Short Answer</p>
                <h2 class="mt-1 text-lg sm:text-xl font-extrabold text-slate-900 break-words">
                  {{ $q->text }}
                </h2>
                <p class="mt-1 text-sm text-slate-600">
                  Points:
                  <span class="font-extrabold text-slate-900">{{ $q->points }}</span>
                </p>
              </div>

              <div class="flex items-center gap-2 shrink-0">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-extrabold border bg-white"
                      style="border-color: rgba(237,183,10,.30);">
                  <span class="w-2 h-2 rounded-full" style="background: {{ $cmYellow }};"></span>
                  Grade
                </span>
              </div>
            </div>

            <div class="cm-panel-body">
              <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">

                {{-- Student answer --}}
                <div class="lg:col-span-8 min-w-0">
                  <div class="rounded-2xl border p-4 overflow-hidden"
                       style="border-color: rgba(226,232,240,1); background: rgba(225, 255, 74, 0.31);">
                    <p class="text-xs font-bold tracking-widest uppercase text-slate-500">Student Answer</p>

                    {{-- ✅ Fix overflow: wrap long unbroken strings --}}
                    <p class="mt-2 text-sm text-slate-800 cm-wrap">
                      {{ $ans->short_answer ?? '—' }}
                    </p>
                  </div>
                </div>

                {{-- Grade selector --}}
                <div class="lg:col-span-4 min-w-0">
                  <div class="rounded-2xl border p-4 bg-white overflow-hidden"
                       style="border-color: rgba(226,232,240,1);">
                    <p class="text-sm font-extrabold text-slate-900">Mark</p>
                    <p class="mt-1 text-xs text-slate-600">Choose correct or wrong.</p>

                    <label class="sr-only" for="grade-{{ $ans->id }}">Correct?</label>
                    <select id="grade-{{ $ans->id }}"
                            name="grades[{{ $ans->id }}]"
                            class="cm-input mt-3 w-full">
                      <option value="0" {{ $ans->is_correct === false ? 'selected' : '' }}>Wrong</option>
                      <option value="1" {{ $ans->is_correct === true ? 'selected' : '' }}>Correct</option>
                    </select>

                    <div class="mt-3 rounded-xl border p-3 text-xs text-slate-700 cm-wrap"
                         style="border-color: rgba(226,232,240,1); background: rgba(222, 99, 99, 0.23);">
                      Tip: Mark “Correct” only when the answer matches your expected response.
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </section>
        @endif
      @endforeach

      @if(!$hasShort)
        <div class="cm-panel cm-reveal" style="border-color: rgba(237,183,10,.22);">
          <div class="cm-panel-head" style="background: rgba(237,183,10,.10);">
            <div>
              <h2 class="text-lg font-extrabold">Nothing to grade</h2>
              <p class="mt-1 text-sm text-slate-600">This attempt has no short-answer questions.</p>
            </div>
          </div>
          <div class="cm-panel-body">
            <p class="text-sm text-slate-700">
              MCQ / True-False questions are automatically scored. You can go back to the grading list.
            </p>
          </div>
        </div>
      @endif

      {{-- Sticky-ish action row --}}
      <div class="cm-reveal flex flex-wrap gap-2">
        <button type="submit"
                class="cm-btn-solid"
                style="--btn: {{ $cmGreen }}; --btn-text:#FFFFFF;">
          Save Grading + Update Score
        </button>

        <a href="{{ route('quizzes.grading.index', $quiz) }}"
           class="cm-btn-outline"
           style="--btn-border: rgba(255, 30, 30, 1); --btn-text:rgba(255, 30, 30, 1); --btn-bg: rgba(255, 255, 255, 1);">
          Cancel
        </a>
      </div>
    </form>

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
        padding:1.25rem 1.25rem 1rem 1.25rem;
        border-bottom:1px solid rgba(226,232,240,1);
        display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;
      }
      .cm-panel-body{ padding:1.25rem; }

      .cm-input{
        border-radius:14px;
        border:1px solid rgba(226,232,240,1);
        background:#fff;
        padding:.78rem 1rem;
        outline:none;
        transition: box-shadow .2s ease, border-color .2s ease;
      }
      .cm-input:focus{
        border-color: rgba(36,99,235,.35);
        box-shadow: 0 0 0 4px rgba(36,99,235,.12);
      }

      .cm-btn-solid{
        display:inline-flex;align-items:center;justify-content:center;
        padding:.62rem 1rem;border-radius:9999px;border:2px solid transparent;
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

      .cm-btn-outline{
        display:inline-flex;align-items:center;justify-content:center;
        padding:.62rem 1rem;border-radius:9999px;
        border:2px solid var(--btn-border, rgba(226,232,240,1));
        background: var(--btn-bg, #fff);
        color: var(--btn-text, #0f172a);
        font-weight:950;
        box-shadow:0 6px 16px rgba(15,23,42,.08);
        transition:transform .15s ease, box-shadow .2s ease, background .2s ease;
        white-space:nowrap;
      }
      .cm-btn-outline:hover{
        transform:translateY(-1px);
        box-shadow:0 12px 26px rgba(15,23,42,.12);
        background: rgba(255, 255, 255, 1);
      }

      .cm-alert{
        border:1px solid rgba(226,232,240,1);
        border-radius: 1.25rem;
        padding: 1rem 1.1rem;
      }
      .cm-alert-dot{
        width:10px;height:10px;border-radius:9999px;margin-top:.35rem;flex:none;
        box-shadow: 0 0 0 4px rgba(255,255,255,.55);
      }

      /* ✅ FIX: contain long words/strings everywhere inside panels */
      .cm-wrap{
        white-space: pre-wrap;
        overflow-wrap: anywhere;
        word-break: break-word;
        max-width: 100%;
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
