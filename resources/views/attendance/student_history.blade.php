@extends('layouts.app')

@section('content')
@php
  $cmBlue   = '#2463EB';
  $cmGreen  = '#8BDE63';
  $cmYellow = '#EDB70A';
@endphp

{{-- ✅ Solid cmGreen wrapper (NO page scrollbar added) --}}
<div class="min-h-[calc(100vh-88px)]" style="background: {{ $cmGreen }};">

  {{-- ✅ Navbar bottom border --}}
  <div class="h-[6px] w-full"
       style="background: linear-gradient(90deg, {{ $cmBlue }}, {{ $cmGreen }}, {{ $cmYellow }});"></div>

  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-10">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
      <div class="text-center sm:text-left">
        <p class="text-xs font-extrabold tracking-widest uppercase text-white/90">
          Attendance
        </p>
        <h1 class="mt-2 text-3xl sm:text-4xl font-extrabold text-white">
          Your Attendance History
        </h1>
        <p class="mt-2 text-sm sm:text-base text-white/85">
          All your marked sessions are listed here.
        </p>
      </div>

      <a href="{{ route('attendance.join.form') }}"
         class="cm-btn-solid"
         style="--btn: {{ $cmBlue }}; --btn-text:#ffffff;">
        + Join Attendance
      </a>
    </div>

    {{-- ✅ Main card (blue-tinted like quiz history) --}}
    <div class="mt-6 cm-card">

      <div class="flex items-center justify-between gap-3">
        <div>
          <h2 class="text-lg font-extrabold text-white">Sessions</h2>
          <p class="text-sm text-white/90">Newest marks appear first.</p>
        </div>

        {{-- Status legend --}}
        <div class="hidden sm:flex items-center gap-2">
          <span class="cm-pill" style="--dot: {{ $cmGreen }};">Active</span>
          <span class="cm-pill" style="--dot: {{ $cmYellow }};">Expired</span>
          <span class="cm-pill" style="--dot: #94a3b8;">Ended</span>
        </div>
      </div>

      {{-- ✅ ONLY THIS AREA SCROLLS --}}
      <div class="mt-5 cm-scroll overflow-auto pr-1" style="max-height: 420px;">

        <div class="overflow-x-auto rounded-2xl border bg-white"
             style="border-color: rgba(226,232,240,1); box-shadow: 0 10px 26px rgba(15,23,42,.06);">

          <table class="min-w-full">
            <thead style="background: rgba(36,99,235,.06);">
              <tr class="text-left text-xs font-extrabold uppercase tracking-wider text-slate-600">
                <th class="px-5 py-4">Session Code</th>
                <th class="px-5 py-4">Teacher</th>
                <th class="px-5 py-4">Marked Time</th>
                <th class="px-5 py-4">Status</th>
              </tr>
            </thead>

            <tbody class="divide-y" style="border-color: rgba(226,232,240,1);">
              @forelse($rows as $r)
                @php
                  $s = $r->session;

                  $status = 'Ended';
                  $bg = 'rgba(148,163,184,.20)';
                  $dot = '#94a3b8';
                  $fg = '#0f172a';

                  if($s && !$s->ended_at && (!$s->expires_at || $s->expires_at->isFuture())){
                    $status = 'Active';
                    $bg = 'rgba(139,222,99,.24)';
                    $dot = $cmGreen;
                    $fg = '#0B1B0F';
                  } elseif($s && $s->expires_at && $s->expires_at->isPast() && !$s->ended_at) {
                    $status = 'Expired';
                    $bg = 'rgba(237,183,10,.24)';
                    $dot = $cmYellow;
                    $fg = '#3a2b00';
                  }
                @endphp

                <tr class="hover:bg-slate-50/70 transition">
                  <td class="px-5 py-4 font-extrabold tracking-[0.18em] text-slate-900">
                    {{ $s?->session_code ?? '—' }}
                  </td>

                  <td class="px-5 py-4 text-sm text-slate-700">
                    {{ $s?->teacher?->name ?? 'Teacher' }}
                  </td>

                  <td class="px-5 py-4 text-sm text-slate-700">
                    {{ optional($r->marked_at)?->timezone('Asia/Dhaka')->format('Y-m-d h:i A') ?? '—' }}
                  </td>

                  <td class="px-5 py-4">
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-extrabold border"
                          style="border-color: rgba(15,23,42,.10); background: {{ $bg }}; color: {{ $fg }};">
                      <span class="w-2 h-2 rounded-full" style="background: {{ $dot }};"></span>
                      {{ $status }}
                    </span>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="px-5 py-10 text-center text-sm font-semibold text-slate-600">
                    No attendance found yet.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>

        </div>
      </div>

      {{-- Pagination stays OUTSIDE scroll --}}
      <div class="mt-4">
        {{ $rows->links() }}
      </div>
    </div>

    {{-- Styles --}}
    <style>
      .cm-card{
        background: rgba(36,99,235,.14);
        border: 1px solid rgba(255,255,255,.55);
        border-radius: 1.5rem;
        padding: 1.5rem;
        box-shadow: 0 18px 45px rgba(15,23,42,.14);
      }

      .cm-pill{
        display:inline-flex;
        align-items:center;
        gap:.4rem;
        padding:.35rem .75rem;
        border-radius:9999px;
        font-size:11px;
        font-weight:900;
        background: rgba(36,99,235,.10);
        color:#0f172a;
        border:1px solid rgba(36,99,235,.18);
      }
      .cm-pill::before{
        content:'';
        width:8px;height:8px;border-radius:9999px;
        background: var(--dot);
      }

      .cm-btn-solid{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        padding:.72rem 1.1rem;
        border-radius:9999px;
        font-weight:950;
        background: var(--btn);
        color: var(--btn-text,#fff);
        box-shadow:0 12px 28px rgba(36,99,235,.35);
        transition:transform .15s ease, box-shadow .2s ease;
      }
      .cm-btn-solid:hover{
        transform:translateY(-1px);
        box-shadow:0 18px 34px rgba(36,99,235,.42);
      }

      /* ✅ scrollbar ONLY for list */
      .cm-scroll::-webkit-scrollbar{width:8px}
      .cm-scroll::-webkit-scrollbar-track{background:rgba(15,23,42,.06);border-radius:9999px}
      .cm-scroll::-webkit-scrollbar-thumb{background:rgba(15,23,42,.18);border-radius:9999px}
      .cm-scroll::-webkit-scrollbar-thumb:hover{background:rgba(15,23,42,.26)}
    </style>

  </div>
</div>
@endsection
