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

    {{-- Header (no card) --}}
    <div class="cm-animate-in">
      <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div class="min-w-0">
          <p class="text-xs font-bold tracking-widest uppercase text-white/80">Teacher • Manual Grading</p>
          <h1 class="mt-2 text-2xl sm:text-3xl lg:text-4xl font-extrabold leading-tight text-white truncate">
            {{ $quiz->title }}
          </h1>
          <p class="mt-2 text-sm sm:text-base text-white/85">
            Open an attempt and grade short answers.
          </p>
        </div>

        <div class="flex flex-wrap gap-2 shrink-0">
          <a href="{{ route('quizzes.manage', $quiz) }}"
             class="cm-btn-solid"
             style="--btn: {{ $cmGreen }}; --btn-text:#0f172a;">
            Back to Manage
          </a>
        </div>
      </div>
    </div>

    {{-- Attempts table panel --}}
    <section class="mt-6 lg:mt-8 cm-panel cm-reveal" style="border-color: rgba(36,99,235,.18);">
      <div class="cm-panel-head" style="background: rgba(36,99,235,.08);">
        <div>
          <h2 class="text-xl font-extrabold">Attempts</h2>
          <p class="mt-1 text-sm text-slate-600">Select an attempt to grade short answers.</p>
        </div>

        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-extrabold border bg-white"
              style="border-color: rgba(36,99,235,.18); color: {{ $cmBlue }};">
          <span class="w-2 h-2 rounded-full" style="background: {{ $cmBlue }};"></span>
          Grading
        </span>
      </div>

      <div class="cm-panel-body p-0">
        <div class="overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead style="background: rgba(15,23,42,.03);">
              <tr class="text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                <th class="px-5 py-4">Student</th>
                <th class="px-5 py-4">Email</th>
                <th class="px-5 py-4">Submitted</th>
                <th class="px-5 py-4">Score</th>
                <th class="px-5 py-4 text-right">Action</th>
              </tr>
            </thead>

            <tbody class="divide-y divide-slate-200">
              @forelse($attempts as $a)
                <tr class="hover:bg-slate-50/70 transition">
                  <td class="px-5 py-4 font-extrabold text-slate-900">
                    {{ $a->student?->name ?? 'Student' }}
                  </td>

                  <td class="px-5 py-4 text-slate-700">
                    {{ $a->student?->email ?? '—' }}
                  </td>

                  <td class="px-5 py-4 text-slate-700">
                    {{ optional($a->submitted_at)->timezone(config('app.timezone'))->format('Y-m-d h:i A') ?? 'Not submitted' }}
                  </td>

                  <td class="px-5 py-4 font-extrabold tabular-nums" style="color: {{ $cmBlue }};">
                    {{ (int)($a->score ?? 0) }}
                  </td>

                  <td class="px-5 py-4 text-right">
                    <a href="{{ route('quizzes.grading.show', [$quiz, $a]) }}"
                       class="cm-btn-solid"
                       style="--btn: {{ $cmYellow }}; --btn-text:#111827;">
                      Grade
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="px-5 py-10 text-center text-sm text-slate-600">
                    No attempts yet.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </section>

    {{-- Pagination --}}
    <div class="mt-4 cm-reveal">
      {{ $attempts->links() }}
    </div>

    <div class="h-8"></div>

    {{-- Styles (same system as other pages) --}}
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
        display:flex;
        align-items:flex-start;
        justify-content:space-between;
        gap:1rem;
      }
      .cm-panel-body{ padding:1.25rem; }

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
