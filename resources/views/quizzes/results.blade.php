@extends('layouts.app')

@section('content')
@php
  $cmBlue='#2463EB'; $cmGreen='#8BDE63'; $cmYellow='#EDB70A';

  // safe defaults (in case)
  $maxScore = $maxScore ?? 0;
  $totalSubmissions = $totalSubmissions ?? 0;
  $avgScore = $avgScore ?? null;
  $topScore = $topScore ?? null;
@endphp

<div class="min-h-[calc(100vh-88px)] text-slate-900" style="background:#2463EB;">
  {{-- Top accent strip --}}
  <div class="h-[6px] w-full" style="background: linear-gradient(90deg, {{ $cmBlue }}, {{ $cmGreen }}, {{ $cmYellow }});"></div>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">

    {{-- HERO (same vibe as Teacher Dashboard) --}}
    <section class="cm-hero cm-animate-in rounded-3xl border border-slate-200 bg-white overflow-hidden shadow-[0_10px_30px_rgba(15,23,42,0.06)]">
      <div class="relative">
        {{-- Soft ambient background blobs --}}
        <div class="absolute inset-0 pointer-events-none">
          <div class="absolute -top-24 -left-24 w-[520px] h-[520px] rounded-full blur-3xl" style="background: rgba(36,99,235,.10)"></div>
          <div class="absolute -bottom-24 -right-24 w-[560px] h-[560px] rounded-full blur-3xl" style="background: rgba(139,222,99,.12)"></div>
          <div class="absolute top-10 right-8 w-[360px] h-[360px] rounded-full blur-3xl" style="background: rgba(237,183,10,.10)"></div>
        </div>

        <div class="relative p-6 sm:p-8">
          <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
            <div>
              <p class="text-xs font-bold tracking-widest uppercase text-slate-500">Teacher • Quiz Results</p>

              <h1 class="mt-2 text-2xl sm:text-3xl lg:text-4xl font-extrabold leading-tight">
                {{ $quiz->title }}
              </h1>

              <p class="mt-2 text-sm sm:text-base text-slate-600">
                Max score: <span class="font-extrabold text-slate-900">{{ $maxScore }}</span>
                <span class="mx-2">•</span>
                Status: <span class="font-extrabold text-slate-900">{{ strtoupper($quiz->status) }}</span>
              </p>
            </div>

            {{-- Actions (teacher dashboard button style) --}}
            <div class="flex flex-wrap gap-2 sm:gap-3">
  
              <a href="{{ route('quizzes.grading.index', $quiz) }}"
                 class="cm-btn-solid"
                 style="--btn: {{ $cmGreen }}; --btn-text:#FFFFFF;">
                Manual Grading
              </a>
            </div>
          </div>

          {{-- KPI STRIP (same feeling as dashboard KPIs) --}}
          <div class="mt-6 sm:mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Total submissions --}}
            <div class="cm-kpi cm-reveal" style="background: rgba(36, 235, 86, 0.1); border-color: rgba(36, 235, 86, .18);">
              <div class="flex items-center justify-between gap-3">
                <div>
                  <p class="text-xs font-bold uppercase tracking-wider text-slate-700">Total Submissions</p>
                  <p class="mt-1 text-3xl font-extrabold tabular-nums text-slate-900">{{ $totalSubmissions }}</p>
                  <p class="mt-1 text-sm text-slate-800">Attempts received</p>
                </div>
                <div class="cm-kpi-icon" style="background: rgba(255,255,255,.9); border-color: rgba(36,99,235,.25);">
                  <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" style="color: {{ $cmBlue }};">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M4 19V5M20 19H4M8 15v-4M12 15V9M16 15v-7" />
                  </svg>
                </div>
              </div>
              <div class="mt-3 text-xs text-slate-700">
                Sorted by score (high → low)
              </div>
            </div>

            {{-- Average score --}}
            <div class="cm-kpi cm-reveal" style="background: rgba(36,99,235,.12); border-color: rgba(36,99,235,.22);">
              <div class="flex items-center justify-between gap-3">
                <div>
                  <p class="text-xs font-bold uppercase tracking-wider text-slate-700">Average Score</p>
                  <p class="mt-1 text-3xl font-extrabold tabular-nums" style="color: {{ $cmBlue }};">
                    {{ $avgScore === null ? '—' : $avgScore }}
                    <span class="text-base font-bold text-slate-500">/ {{ $maxScore }}</span>
                  </p>
                  <p class="mt-1 text-sm text-slate-800">Overall performance</p>
                </div>
                <div class="cm-kpi-icon" style="background: rgba(255,255,255,.9); border-color: rgba(36,99,235,.25);">
                  <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" style="color: {{ $cmBlue }};">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 20l9-5-9-5-9 5 9 5z" />
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 12l9-5-9-5-9 5 9 5z" opacity=".35" />
                  </svg>
                </div>
              </div>
              <div class="mt-3 h-2 rounded-full bg-white/80 overflow-hidden border border-white/60">
                @php
                  $avgProg = ($avgScore === null || $maxScore <= 0) ? 0 : max(0, min(100, ($avgScore / max(1, $maxScore)) * 100));
                @endphp
                <div class="h-full rounded-full" style="width: {{ $avgProg }}%; background: {{ $cmBlue }};"></div>
              </div>
            </div>

            {{-- Top score --}}
            <div class="cm-kpi cm-reveal" style="background: rgba(139,222,99,.18); border-color: rgba(139,222,99,.28);">
              <div class="flex items-center justify-between gap-3">
                <div>
                  <p class="text-xs font-bold uppercase tracking-wider text-slate-700">Top Score</p>
                  <p class="mt-1 text-3xl font-extrabold tabular-nums" style="color: {{ $cmGreen }};">
                    {{ $topScore === null ? '—' : $topScore }}
                    <span class="text-base font-bold text-slate-500">/ {{ $maxScore }}</span>
                  </p>
                  <p class="mt-1 text-sm text-slate-800">Best submission</p>
                </div>
                <div class="cm-kpi-icon" style="background: rgba(255,255,255,.9); border-color: rgba(139,222,99,.35);">
                  <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" style="color: {{ $cmGreen }};">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M20 6L9 17l-5-5" />
                  </svg>
                </div>
              </div>
              <div class="mt-3 h-2 rounded-full bg-white/80 overflow-hidden border border-white/60">
                @php
                  $topProg = ($topScore === null || $maxScore <= 0) ? 0 : max(0, min(100, ($topScore / max(1, $maxScore)) * 100));
                @endphp
                <div class="h-full rounded-full" style="width: {{ $topProg }}%; background: {{ $cmGreen }};"></div>
              </div>
            </div>

            {{-- Quick actions (small) --}}
            <div class="cm-kpi cm-reveal" style="background: rgba(237,183,10,.16); border-color: rgba(237,183,10,.26);">
              <div class="flex items-center justify-between gap-3">
                <div>
                  <p class="text-xs font-bold uppercase tracking-wider text-slate-700">Quick Actions</p>
                  <p class="mt-1 text-lg font-extrabold text-slate-900">Tools</p>
                  <p class="mt-1 text-sm text-slate-800">Common actions</p>
                </div>
                <div class="cm-kpi-icon" style="background: rgba(255,255,255,.9); border-color: rgba(237,183,10,.30);">
                  <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" style="color: {{ $cmYellow }};">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a8 8 0 100 16 8 8 0 000-16zm0 8l3 3" />
                  </svg>
                </div>
              </div>

              <div class="mt-3 flex flex-wrap gap-2">
                <a href="{{ route('quizzes.manage', $quiz) }}" class="cm-btn-outline" style="--btn: {{ $cmBlue }}; --btn-text: {{ $cmBlue }};">
                  Manage
                </a>
                <a href="{{ route('quizzes.leaderboard', $quiz) }}" class="cm-btn-solid" style="--btn: {{ $cmYellow }}; --btn-text:#FFFFFF;">
                  Leaderboard
                </a>
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>

    {{-- TABLE PANEL (same cm-panel system) --}}
    <section class="mt-6 lg:mt-8 cm-panel cm-reveal" style="border-color: rgba(36,99,235,.18);">
      <div class="cm-panel-head" style="background: rgba(36,99,235,.08);">
        <div>
          <h2 class="text-xl font-extrabold">Submissions</h2>
          <p class="mt-1 text-sm text-slate-600">Sorted by score (desc)</p>
        </div>

        <div class="flex items-center gap-2">
          <span class="inline-flex items-center gap-2 text-xs font-bold text-slate-700">
            <span class="w-2.5 h-2.5 rounded-full" style="background: {{ $cmBlue }};"></span>
            Results
          </span>
        </div>
      </div>

      <div class="cm-panel-body p-0">
        <div class="overflow-x-auto">
          <table class="min-w-full">
            <thead style="background: rgba(15,23,42,.03);">
              <tr class="text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                <th class="px-5 py-4">Student</th>
                <th class="px-5 py-4">Email</th>
                <th class="px-5 py-4">Score</th>
                <th class="px-5 py-4">Submitted</th>
              </tr>
            </thead>

            <tbody class="divide-y divide-slate-200">
              @forelse($attempts as $a)
                <tr class="hover:bg-slate-50/70 transition">
                  <td class="px-5 py-4 font-semibold text-slate-900">
                    {{ $a->student?->name ?? 'Student' }}
                  </td>
                  <td class="px-5 py-4 text-sm text-slate-700">
                    {{ $a->student?->email ?? '—' }}
                  </td>
                  <td class="px-5 py-4 font-extrabold tabular-nums" style="color:{{ $cmBlue }};">
                    {{ (int)$a->score }}
                    <span class="text-slate-500 font-bold">/ {{ $maxScore }}</span>
                  </td>
                  <td class="px-5 py-4 text-sm text-slate-700">
                    {{ optional($a->submitted_at)?->timezone('Asia/Dhaka')->format('Y-m-d h:i A') ?? '—' }}
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="px-5 py-10 text-center text-sm text-slate-600">
                    No one has submitted this quiz yet.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <div class="px-5 py-4 border-t border-slate-200">
          {{ $attempts->links() }}
        </div>
      </div>
    </section>

    {{-- Shared styles (same as Teacher Dashboard buttons/panels) --}}
    <style>
      .cm-animate-in{opacity:0;transform:translateY(10px);animation:cmIn .55s ease forwards;}
      @keyframes cmIn{to{opacity:1;transform:translateY(0);}}

      .cm-reveal{opacity:0;transform:translateY(14px);}
      .cm-reveal.cm-in{opacity:1;transform:translateY(0);transition:opacity .55s ease, transform .55s ease;}

      .cm-kpi{
        border:1px solid rgba(226,232,240,1);
        border-radius:1.25rem;
        padding:.85rem;
        box-shadow:0 1px 0 rgba(15,23,42,.04);
        transition:transform .2s ease, box-shadow .2s ease;
      }
      .cm-kpi:hover{transform:translateY(-2px);box-shadow:0 14px 34px rgba(15,23,42,.08);}

      .cm-kpi-icon{
        width:40px;height:40px;border-radius:14px;
        display:grid;place-items:center;border:1px solid rgba(226,232,240,1);
      }

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

      /* SOLID */
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

      /* OUTLINE */
      .cm-btn-outline{
        --btn:#2463EB; --btn-text:var(--btn);
        display:inline-flex;align-items:center;justify-content:center;
        padding:.62rem 1rem;border-radius:9999px;
        border:2px solid var(--btn);
        background:#fff;color:var(--btn-text);
        font-weight:950;
        box-shadow:0 6px 16px rgba(15,23,42,.08);
        transition:transform .15s ease, box-shadow .2s ease, background .2s ease;
        white-space:nowrap;
      }
      .cm-btn-outline:hover{transform:translateY(-1px);box-shadow:0 12px 26px rgba(15,23,42,.12);background:rgba(255,255,255,.95);}
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
