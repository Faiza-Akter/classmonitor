@extends('layouts.app')

@section('content')
  @php
    $cmBlue = '#2463EB';
    $cmGreen = '#8BDE63';
    $cmYellow = '#EDB70A';
    $cmRed = '#EF4444';
  @endphp

  <div class="min-h-[calc(100vh-88px)] text-slate-900 relative overflow-x-hidden" style="background:#8BDE63;">
    {{-- Top accent strip --}}
    <div class="h-[6px] w-full"
      style="background: linear-gradient(90deg, {{ $cmBlue }}, {{ $cmGreen }}, {{ $cmYellow }});"></div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8 overflow-x-hidden">

      {{-- Header (NO card) --}}
      <div class="cm-animate-in">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
          <div class="min-w-0">
            <p class="text-xs font-bold tracking-widest uppercase text-white/80">Student • Quiz</p>
            <h1 class="mt-2 text-2xl sm:text-3xl lg:text-4xl font-extrabold leading-tight text-white truncate">
              {{ $quiz->title }}
            </h1>
            <p class="mt-2 text-sm sm:text-base text-white/85">
              Answer all questions and submit.
            </p>
          </div>

          @if(isset($remainingSeconds) && $remainingSeconds !== null)
            <div class="shrink-0">
              <div class="cm-timer cm-reveal">
                <p class="text-xs font-extrabold uppercase tracking-widest text-slate-600">Time Left</p>
                <p id="timer" class="text-2xl font-extrabold tabular-nums" style="color: {{ $cmBlue }};">--:--</p>
              </div>
            </div>
          @endif
        </div>
      </div>

      {{-- Errors --}}
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

      {{-- Form --}}
      <form id="quizForm" class="mt-6 lg:mt-8 space-y-5"
            method="POST" action="{{ route('quizzes.submit', $quiz) }}">
        @csrf

        @foreach($quiz->questions as $q)
          @php
            $type = $q->type ?? 'mcq';
            $badge = strtoupper($type);

            // badge palette
            $badgeBg = $type === 'short'
              ? 'rgba(36,99,235,.14)'
              : ($type === 'tf' ? 'rgba(139,222,99,.18)' : 'rgba(237,183,10,.18)');

            $badgeColor = $type === 'short'
              ? '#0b2a7a'
              : ($type === 'tf' ? '#0B1B0F' : '#3a2b00');

            $panelBorder = $type === 'short'
              ? 'rgba(36,99,235,.18)'
              : ($type === 'tf' ? 'rgba(139,222,99,.22)' : 'rgba(237,183,10,.22)');

            $panelHeadBg = $type === 'short'
              ? 'rgba(36,99,235,.08)'
              : ($type === 'tf' ? 'rgba(139,222,99,.10)' : 'rgba(237,183,10,.10)');
          @endphp

          <section class="cm-panel cm-reveal" style="border-color: {{ $panelBorder }};">
            <div class="cm-panel-head" style="background: {{ $panelHeadBg }};">
              <div class="min-w-0">
                <p class="font-extrabold text-slate-900 break-words">{{ $q->text }}</p>
                <p class="mt-1 text-sm text-slate-600">Points: <span class="font-extrabold">{{ $q->points }}</span></p>
              </div>

              <span class="shrink-0 inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold border bg-white"
                    style="border-color: rgba(15,23,42,.10); background: {{ $badgeBg }}; color: {{ $badgeColor }};">
                {{ $badge }}
              </span>
            </div>

            <div class="cm-panel-body">
              {{-- MCQ / TF --}}
              @if(in_array($type, ['mcq', 'tf']))
                <div class="space-y-2">
                  @foreach($q->options as $opt)
                    <label class="cm-option">
                      <input class="cm-radio" type="radio" name="answers[{{ $q->id }}]" value="{{ $opt->id }}" required>
                      <span class="text-sm text-slate-800">{{ $opt->text }}</span>
                    </label>
                  @endforeach
                </div>
              @endif

              {{-- SHORT --}}
              @if($type === 'short')
                <div>
                  <label class="block text-sm font-extrabold text-slate-700">Your Answer</label>
                  <textarea name="short[{{ $q->id }}]" rows="4" required
                            class="cm-input mt-2 w-full"
                            placeholder="Write your answer here...">{{ old("short.$q->id") }}</textarea>
                  <p class="mt-2 text-xs text-slate-500">
                    This question will be graded by the teacher.
                  </p>
                </div>
              @endif
            </div>
          </section>
        @endforeach

        <div class="cm-reveal flex flex-wrap gap-2">
          <button type="submit"
                  class="cm-btn-solid"
                  style="--btn: {{ $cmBlue }}; --btn-text:#FFFFFF;">
            Submit Quiz
          </button>
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
          padding:1.15rem 1.25rem 1rem 1.25rem;
          border-bottom:1px solid rgba(226,232,240,1);
          display:flex;
          align-items:flex-start;
          justify-content:space-between;
          gap:1rem;
        }
        .cm-panel-body{ padding:1.25rem; }

        .cm-timer{
          border: 1px solid rgba(255,255,255,.35);
          background: rgba(255,255,255,.92);
          border-radius: 1.25rem;
          padding: .85rem 1rem;
          box-shadow: 0 10px 26px rgba(15,23,42,.10);
          min-width: 170px;
        }

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

        .cm-option{
          display:flex;
          align-items:flex-start;
          gap:.75rem;
          border: 1px solid rgba(226,232,240,1);
          background: rgba(15,23,42,.03);
          padding: .85rem 1rem;
          border-radius: 1rem;
          cursor:pointer;
          transition: background .2s ease, transform .15s ease, box-shadow .2s ease;
        }
        .cm-option:hover{
          background:#fff;
          box-shadow:0 10px 22px rgba(15,23,42,.06);
          transform: translateY(-1px);
        }

        .cm-radio{
          margin-top: .18rem;
          accent-color: {{ $cmBlue }};
        }

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

        .cm-alert{
          border:1px solid rgba(226,232,240,1);
          border-radius: 1.25rem;
          padding: 1rem 1.1rem;
          background: #fff;
        }
        .cm-alert-dot{
          width:10px;height:10px;border-radius:9999px;margin-top:.35rem;flex:none;
          box-shadow: 0 0 0 4px rgba(255,255,255,.55);
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

  @if(isset($remainingSeconds) && $remainingSeconds !== null)
    <script>
      let remaining = Math.max(0, parseInt(@json($remainingSeconds), 10) || 0);
      let submitted = false;

      function fmt(sec) {
        const m = Math.floor(sec / 60);
        const s = sec % 60;
        return String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
      }

      const timerEl = document.getElementById('timer');
      const form = document.getElementById('quizForm');

      function lockForm() {
        if (submitted) return;
        submitted = true;

        const btn = form.querySelector('button[type="submit"]');
        if (btn) {
          btn.disabled = true;
          btn.textContent = 'Submitting...';
          btn.style.opacity = '0.85';
          btn.style.cursor = 'not-allowed';
        }
      }

      form.addEventListener('submit', () => lockForm());

      function tick() {
        if (timerEl) timerEl.textContent = fmt(Math.max(0, remaining));

        if (remaining <= 0) {
          lockForm();
          form.submit();
          return;
        }

        remaining -= 1;
        setTimeout(tick, 1000);
      }

      tick();
    </script>
  @endif
@endsection
