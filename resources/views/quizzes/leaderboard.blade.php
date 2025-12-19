@extends('layouts.app')

@section('content')
@php
  $cmBlue='#2463EB'; $cmGreen='#8BDE63'; $cmYellow='#EDB70A';
  $maxScore = $maxScore ?? 0;
@endphp

<div class="min-h-[calc(100vh-88px)] text-slate-900 relative overflow-x-hidden pb-10" style="background:#2463EB;">
  {{-- Top accent strip --}}
  <div class="h-[6px] w-full"
       style="background: linear-gradient(90deg, {{ $cmBlue }}, {{ $cmGreen }}, {{ $cmYellow }});"></div>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8 overflow-x-hidden">

    {{-- HEADER (NO background card, keep texts + buttons) --}}
    <div class="cm-animate-in">
      <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div class="min-w-0">
          <p class="text-xs font-bold tracking-widest uppercase text-white/80">Teacher • Leaderboard</p>

          <h1 class="mt-2 text-2xl sm:text-3xl lg:text-4xl font-extrabold leading-tight text-white truncate">
            {{ $quiz->title }}
          </h1>

          <p class="mt-2 text-sm sm:text-base text-white/85">
            Ranked by score
            <span class="mx-2">•</span>
            Tie-break: earlier submit
            <span class="mx-2">•</span>
            Max score: <span class="font-extrabold text-white">{{ $maxScore }}</span>
          </p>
        </div>

        <div class="flex flex-wrap gap-2 sm:gap-3">
          <a href="{{ route('quizzes.results', $quiz) }}"
             class="cm-btn-solid"
             style="--btn: {{ $cmYellow }}; --btn-text:#FFFFFF;">
            Back to Results
          </a>

          <a href="{{ route('quizzes.manage', $quiz) }}"
             class="cm-btn-solid"
             style="--btn: {{ $cmGreen }}; --btn-text:#FFFFFF;">
            Back to Manage
          </a>
        </div>
      </div>
    </div>

    {{-- LEADERBOARD PANEL (no KPI strip) --}}
    <section class="mt-6 lg:mt-8 cm-panel cm-reveal overflow-hidden" style="border-color: rgba(36,99,235,.18);">
      <div class="cm-panel-head" style="background: rgba(36,99,235,.08);">
        <div>
          <h2 class="text-xl font-extrabold">Top Results</h2>
          <p class="mt-1 text-sm text-slate-600">Highest score first • Earlier submit wins ties</p>
        </div>

        <span class="text-xs font-bold text-slate-500">
          Max: <span class="text-slate-900">{{ $maxScore }}</span>
        </span>
      </div>

      <div class="cm-panel-body p-0">
        <div class="divide-y divide-slate-200">
          @forelse($attempts as $i => $a)
            @php
              $rank = $i + 1;

              // rank colors (top 3 themed)
              $rankBg = $cmBlue;
              $rankText = '#FFFFFF';

              if ($rank === 1) { $rankBg = $cmYellow; $rankText = '#111827'; }
              elseif ($rank === 2) { $rankBg = $cmBlue; $rankText = '#FFFFFF'; }
              elseif ($rank === 3) { $rankBg = $cmGreen; $rankText = '#0f172a'; }
            @endphp

            <div class="px-5 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 hover:bg-slate-50/70 transition">
              {{-- Left: rank + user --}}
              <div class="flex items-center gap-3 min-w-0">
                <div class="w-10 h-10 rounded-2xl grid place-items-center font-extrabold shadow-sm flex-none"
                     style="background: {{ $rankBg }}; color: {{ $rankText }};">
                  {{ $rank }}
                </div>

                <div class="min-w-0">
                  <div class="flex items-center gap-2 min-w-0">
                    <p class="font-extrabold text-slate-900 truncate">
                      {{ $a->student?->name ?? 'Student' }}
                    </p>

                    @if($rank === 1)
                      <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-extrabold"
                            style="background: rgba(237,183,10,.18); color:#8a6b00; border:1px solid rgba(237,183,10,.28);">
                        Champion
                      </span>
                    @elseif($rank <= 3)
                      <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-extrabold"
                            style="background: rgba(15,23,42,.06); color:#334155; border:1px solid rgba(15,23,42,.08);">
                        Top {{ $rank }}
                      </span>
                    @endif
                  </div>

                  <p class="text-xs text-slate-500 truncate">
                    {{ $a->student?->email ?? '—' }}
                  </p>
                </div>
              </div>

              {{-- Right: score + submitted --}}
              <div class="text-left sm:text-right flex-none">
                <p class="text-lg font-extrabold tabular-nums" style="color: {{ $cmBlue }};">
                  {{ (int)$a->score }}
                  <span class="text-sm font-bold text-slate-500">/ {{ $maxScore }}</span>
                </p>
                <p class="text-xs text-slate-500">
                  {{ optional($a->submitted_at)?->timezone('Asia/Dhaka')->format('Y-m-d h:i A') ?? '—' }}
                </p>
              </div>
            </div>
          @empty
            <div class="px-5 py-10 text-center text-sm text-slate-600">
              No submissions yet.
            </div>
          @endforelse
        </div>
      </div>
    </section>

    {{-- Styles --}}
    <style>
      /* Fix bottom "extra white space" feeling:
         - ensure no horizontal overflow
         - remove accidental default body margin/padding from layout if any
         - keep a small blue bottom padding (pb-10 already) so it looks intentional */
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

      .cm-btn-solid{
        display:inline-flex;align-items:center;justify-content:center;
        padding:.62rem 1rem;border-radius:9999px;border:2px solid transparent;
        background:var(--btn,#2463EB);color:var(--btn-text,#fff);
        font-weight:950;
        box-shadow:0 6px 16px rgba(15,23,42,.10);
        transition:transform .15s ease, box-shadow .2s ease, filter .2s ease;
        white-space:nowrap;
      }
      .cm-btn-solid:hover{transform:translateY(-1px);box-shadow:0 12px 26px rgba(15,23,42,.14);filter:saturate(1.06);}
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
