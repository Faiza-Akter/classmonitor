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

  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8 overflow-x-hidden">

    {{-- Header --}}
    <div class="cm-animate-in">
      <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div class="min-w-0">
          <p class="text-xs font-bold tracking-widest uppercase text-white/80">Teacher • Edit Question</p>
          <h1 class="mt-2 text-2xl sm:text-3xl lg:text-4xl font-extrabold leading-tight text-white truncate">
            {{ $quiz->title }}
          </h1>
          <p class="mt-2 text-sm sm:text-base text-white/85">
            Update question text, points, options and correct answer.
          </p>
        </div>

        <div class="flex gap-2 shrink-0">
          <a href="{{ route('quizzes.manage', $quiz) }}"
             class="cm-btn-solid"
             style="--btn: {{ $cmGreen }}; --btn-text:#0f172a;">
            Back to Manage
          </a>
        </div>
      </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
      <div class="mt-5 cm-alert cm-reveal"
           style="border-color: rgba(139,222,99,.35); background: rgba(139,222,99,.16); color:#0f172a;">
        <p class="font-extrabold">Success</p>
        <p class="text-sm mt-1">{{ session('success') }}</p>
      </div>
    @endif

    @if($errors->any())
      <div class="mt-5 cm-alert cm-reveal"
           style="border-color: rgba(239,68,68,.35); background: rgba(239,68,68,.14); color:#0f172a;">
        <p class="font-extrabold">Please fix the following</p>
        <ul class="mt-2 list-disc pl-5 text-sm">
          @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    {{-- Edit Question Panel --}}
    <section class="mt-6 lg:mt-8 cm-panel cm-reveal" style="border-color: rgba(36,99,235,.18);">
      <div class="cm-panel-head" style="background: rgba(36,99,235,.08);">
        <div>
          <h2 class="text-lg sm:text-xl font-extrabold">Question Details</h2>
          <p class="mt-1 text-sm text-slate-600">
            Type:
            <span class="font-extrabold text-slate-900">{{ strtoupper($question->type ?? 'MCQ') }}</span>
          </p>
        </div>

        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-extrabold border bg-white"
              style="border-color: rgba(237,183,10,.30);">
          <span class="w-2 h-2 rounded-full" style="background: {{ $cmYellow }};"></span>
          {{ strtoupper($question->type ?? 'MCQ') }}
        </span>
      </div>

      <div class="cm-panel-body">
        <form method="POST"
              action="{{ route('quizzes.questions.update', [$quiz, $question]) }}"
              class="space-y-5">
          @csrf
          @method('PATCH')

          {{-- Type --}}
          <div>
            <label class="block text-sm font-extrabold text-slate-700">Question Type</label>
            <select name="type" class="cm-input mt-2 w-full">
              <option value="mcq" @selected(old('type', $question->type) === 'mcq')>MCQ</option>
              <option value="tf" @selected(old('type', $question->type) === 'tf')>True / False</option>
              <option value="short" @selected(old('type', $question->type) === 'short')>Short Answer</option>
            </select>
          </div>

          {{-- Question --}}
          <div>
            <label class="block text-sm font-extrabold text-slate-700">Question</label>
            <textarea name="text" rows="3" required
                      class="cm-input mt-2 w-full"
                      placeholder="Write the question...">{{ old('text', $question->text) }}</textarea>
          </div>

          {{-- Points --}}
          <div>
            <label class="block text-sm font-extrabold text-slate-700">Points</label>
            <input type="number" name="points" min="1" max="100"
                   value="{{ old('points', $question->points ?? 1) }}" required
                   class="cm-input mt-2 w-full">
          </div>

          {{-- Options --}}
          @php
              $existingOptions = $question->options ?? collect();
              $correctIndex = 0;
              foreach($existingOptions as $i => $opt){
                  if($opt->is_correct) { $correctIndex = $i; break; }
              }
          @endphp

          <div class="rounded-2xl border p-4"
               style="border-color: rgba(226,232,240,1); background: rgba(15,23,42,.03);">
            <p class="text-sm font-extrabold text-slate-900">Options</p>
            <p class="text-xs text-slate-600 mt-1">For MCQ: 2–6 options.</p>

            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
              @for($i=0; $i<6; $i++)
                <div>
                  <label class="block text-sm font-extrabold text-slate-700">Option {{ $i+1 }}</label>
                  <input name="options[]"
                         value="{{ old("options.$i", $existingOptions[$i]->text ?? '') }}"
                         class="cm-input mt-2 w-full"
                         placeholder="Option text">
                </div>
              @endfor
            </div>

            <div class="mt-4">
              <label class="block text-sm font-extrabold text-slate-700">Correct Option</label>
              <select name="correct_index" class="cm-input mt-2 w-full">
                @for($i=0; $i<6; $i++)
                  <option value="{{ $i }}"
                    @selected((string)old('correct_index', $correctIndex) === (string)$i)>
                    Option {{ $i+1 }}
                  </option>
                @endfor
              </select>
            </div>
          </div>

          {{-- Actions --}}
          <div class="flex flex-wrap gap-2">
            <button type="submit"
                    class="cm-btn-solid"
                    style="--btn: {{ $cmBlue }}; --btn-text:#FFFFFF;">
              Save Changes
            </button>

            <a href="{{ route('quizzes.manage', $quiz) }}"
               class="cm-btn-outline"
               style="--btn-border: {{ $cmRed }}; --btn-text: {{ $cmRed }};">
              Cancel
            </a>
          </div>
        </form>
      </div>
    </section>

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
        padding:.78rem 1rem;
        background:#fff;
      }
      .cm-input:focus{
        border-color: rgba(36,99,235,.35);
        box-shadow: 0 0 0 4px rgba(36,99,235,.12);
        outline:none;
      }

      .cm-btn-solid{
        display:inline-flex;align-items:center;justify-content:center;
        padding:.62rem 1rem;border-radius:9999px;border:2px solid transparent;
        background:var(--btn);color:var(--btn-text);
        font-weight:950;
        box-shadow:0 6px 16px rgba(15,23,42,.10);
        transition:.2s;
      }
      .cm-btn-solid:hover{transform:translateY(-1px);box-shadow:0 12px 26px rgba(15,23,42,.14);}

      .cm-btn-outline{
        display:inline-flex;align-items:center;justify-content:center;
        padding:.62rem 1rem;border-radius:9999px;
        border:2px solid var(--btn-border);
        background:#fff;color:var(--btn-text);
        font-weight:950;
        transition:.2s;
      }
      .cm-btn-outline:hover{
        background: rgba(255, 255, 255, 1);
        transform:translateY(-1px);
      }

      .cm-alert{
        border-radius:1.25rem;
        padding:1rem 1.1rem;
        border:1px solid rgba(226,232,240,1);
      }
    </style>

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
