@extends('layouts.app')

@section('content')
  @php
    $cmBlue = '#2463EB';
    $cmGreen = '#8BDE63';
    $cmYellow = '#EDB70A';
  @endphp

  {{-- ✅ Solid cmGreen wrapper (NO extra gradients) --}}
  <div class="min-h-[calc(100vh-88px)]" style="background: {{ $cmGreen }};">

    {{-- ✅ Navbar bottom border strip --}}
    <div class="h-[6px] w-full"
      style="background: linear-gradient(90deg, {{ $cmBlue }}, {{ $cmGreen }}, {{ $cmYellow }});"></div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-10">

      {{-- Header --}}
      <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div class="text-center sm:text-left">
          <p class="text-xs font-extrabold tracking-widest uppercase text-white/90">Quizzes</p>
          <h1 class="mt-2 text-3xl sm:text-4xl font-extrabold text-white">Your Quiz History</h1>
          <p class="mt-2 text-sm sm:text-base text-white/85">Submitted attempts and scores.</p>
        </div>

        <a href="{{ route('student.dashboard') }}"
           class="px-5 py-3 rounded-full font-extrabold text-white transition"
           style="background: {{ $cmBlue }}; box-shadow: 0 12px 28px rgba(36,99,235,.35);">
          Back
        </a>
      </div>

      {{-- ✅ Attempts card with BLUE tint --}}
      <div class="mt-6 cm-card">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
          <div>
            <h2 class="text-lg font-extrabold text-white">Attempts</h2>
            <p class="text-sm text-white/90">Latest submissions appear first.</p>
          </div>

          {{-- ✅ Make Submitted + Details chips SAME bg as Score chip (so visible) --}}
          <div class="hidden sm:flex items-center gap-2">
            @php
              $chipBg = 'rgba(36,99,235,.10)';     // same as Score bg
              $chipBd = 'rgba(36,99,235,.20)';     // same as Score border
              $chipTx = 'rgb(15 23 42)';           // readable on the chip
            @endphp

            <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-extrabold border"
                  style="border-color: {{ $chipBd }}; background: {{ $chipBg }}; color: {{ $chipTx }};">
              <span class="w-2 h-2 rounded-full" style="background: {{ $cmBlue }};"></span>
              Score
            </span>

            <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-extrabold border"
                  style="border-color: {{ $chipBd }}; background: {{ $chipBg }}; color: {{ $chipTx }};">
              <span class="w-2 h-2 rounded-full" style="background: {{ $cmGreen }};"></span>
              Submitted
            </span>

            <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-extrabold border"
                  style="border-color: {{ $chipBd }}; background: {{ $chipBg }}; color: {{ $chipTx }};">
              <span class="w-2 h-2 rounded-full" style="background: {{ $cmYellow }};"></span>
              Details
            </span>
          </div>
        </div>

        {{-- ✅ Scroll ONLY the list (not the whole page) --}}
        <div class="mt-5 cm-scroll overflow-auto pr-1" style="max-height: 420px;">
          <div class="space-y-3">
            @forelse($attempts as $a)
              @php
                $quiz = $a->quiz;
                $teacher = $quiz?->teacher?->name ?? 'Teacher';
                $submitted = optional($a->submitted_at)?->timezone('Asia/Dhaka')->format('Y-m-d h:i A');
                $scoreText = is_null($a->submitted_at) ? 'In progress' : ($a->score . ' / ' . ($a->max_score ?? 0));
                $isDone = !is_null($a->submitted_at);
              @endphp

              <div class="rounded-2xl border p-5 bg-white"
                   style="border-color: rgba(226,232,240,1); box-shadow: 0 10px 26px rgba(15,23,42,.06);">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                  <div class="min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                      <p class="text-base font-extrabold text-slate-900 truncate">
                        {{ $quiz?->title ?? 'Quiz' }}
                      </p>

                      @if($isDone)
                        <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-[11px] font-extrabold border"
                              style="border-color: rgba(139,222,99,.30); background: rgba(139,222,99,.16); color:#0B1B0F;">
                          <span class="w-2 h-2 rounded-full" style="background: {{ $cmGreen }};"></span>
                          SUBMITTED
                        </span>
                      @else
                        <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-[11px] font-extrabold border"
                              style="border-color: rgba(237,183,10,.30); background: rgba(237,183,10,.16); color:#3a2b00;">
                          <span class="w-2 h-2 rounded-full" style="background: {{ $cmYellow }};"></span>
                          IN PROGRESS
                        </span>
                      @endif
                    </div>

                    <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-1 text-sm">
                      <p class="text-slate-600">
                        <span class="font-semibold">Teacher:</span> {{ $teacher }}
                      </p>
                      <p class="text-slate-600">
                        <span class="font-semibold">Submitted:</span> {{ $submitted ?? '—' }}
                      </p>
                    </div>
                  </div>

                  <div class="flex items-center gap-3 flex-wrap justify-start md:justify-end">
                    <div class="px-4 py-2 rounded-2xl border"
                         style="border-color: rgba(36,99,235,.18); background: rgba(36,99,235,.08);">
                      <p class="text-xs font-extrabold text-slate-700">Score</p>
                      <p class="text-sm font-extrabold" style="color: {{ $cmBlue }};">
                        {{ $scoreText }}
                      </p>
                    </div>


                    {{-- ✅ solid yellow secondary --}}
                    <a href="{{ route('student.quizzes.show', $a->id) }}"
                       class="cm-btn-solid hidden sm:inline-flex"
                       style="--btn: {{ $cmYellow }}; --btn-text: #1f1600;">
                      Details
                    </a>
                  </div>
                </div>
              </div>
            @empty
              <div class="rounded-2xl border p-6 text-sm font-semibold text-slate-700"
                   style="border-color: rgba(255,255,255,.55); background: rgba(255,255,255,.78);">
                No quiz attempts yet.
              </div>
            @endforelse
          </div>
        </div>
      </div>

      <style>
        /* ✅ Attempts card blue tint */
        .cm-card {
          background: rgba(36, 99, 235, .14);
          border: 1px solid rgba(255, 255, 255, .55);
          border-radius: 1.5rem;
          padding: 1.5rem;
          color: rgb(15 23 42);
          box-shadow: 0 18px 45px rgba(15, 23, 42, .14);
        }

        /* scrollbars only for list area */
        .cm-scroll::-webkit-scrollbar { width: 8px; height: 8px; }
        .cm-scroll::-webkit-scrollbar-track { background: rgba(15, 23, 42, .06); border-radius: 9999px; }
        .cm-scroll::-webkit-scrollbar-thumb { background: rgba(15, 23, 42, .18); border-radius: 9999px; }
        .cm-scroll::-webkit-scrollbar-thumb:hover { background: rgba(15, 23, 42, .26); }

        .cm-btn-solid{
          display:inline-flex; align-items:center; justify-content:center;
          padding:.62rem 1rem; border-radius:9999px; font-weight:950;
          background:var(--btn); color:var(--btn-text,#fff);
          box-shadow:0 6px 16px rgba(15,23,42,.10);
          transition:transform .15s ease, box-shadow .2s ease;
          white-space:nowrap;
        }
        .cm-btn-solid:hover{ transform:translateY(-1px); box-shadow:0 12px 26px rgba(15,23,42,.14); }

        .cm-btn-outline{
          display:inline-flex; align-items:center; justify-content:center;
          padding:.62rem 1rem; border-radius:9999px;
          border:2px solid var(--btn); color:var(--btn-text,var(--btn));
          font-weight:950; background:#fff;
          box-shadow:0 6px 16px rgba(15,23,42,.08);
          transition:transform .15s ease, box-shadow .2s ease;
          white-space:nowrap;
        }
        .cm-btn-outline:hover{ transform:translateY(-1px); box-shadow:0 12px 26px rgba(15,23,42,.12); }

        @media (prefers-reduced-motion: reduce){
          .cm-btn-solid,.cm-btn-outline{ transition:none !important; }
          .cm-btn-solid:hover,.cm-btn-outline:hover{ transform:none !important; }
        }
      </style>

    </div>
  </div>
@endsection
