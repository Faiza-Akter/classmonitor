@extends('layouts.app')

@section('content')
@php
  $cmBlue   = '#2463EB';
  $cmGreen  = '#8BDE63';
  $cmYellow = '#EDB70A';
  $cmRed    = '#EF4444';
@endphp

<div class="min-h-[calc(100vh-88px)] text-white" style="
  background:
    radial-gradient(900px 520px at 18% 10%, rgba(255,255,255,.16) 0%, rgba(255,255,255,0) 60%),
    radial-gradient(760px 460px at 85% 26%, rgba(139,222,99,.22) 0%, rgba(255,255,255,0) 65%),
    radial-gradient(760px 520px at 70% 92%, rgba(237,183,10,.14) 0%, rgba(255,255,255,0) 60%),
    linear-gradient(135deg, rgba(36,99,235,.92) 0%, rgba(36,99,235,.86) 55%, rgba(29,78,216,.92) 100%);
">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
      <div>
        <p class="text-sm font-semibold text-white/90">Attendance Session</p>

        {{-- ✅ remove session code from header (keep clean title only) --}}
        <h1 class="mt-1 text-3xl sm:text-4xl font-extrabold text-white">
          Session Details
        </h1>

        {{-- ✅ Starts/Expires as plain text (no pills/cards) --}}
        <p class="mt-2 text-sm text-white/85">
          <span class="font-semibold text-white">Starts:</span>
          {{ optional($session->starts_at)->timezone(config('app.timezone'))->format('Y-m-d h:i A') ?? '—' }}
          <span class="mx-2 text-white/50">•</span>
          <span class="font-semibold text-white">Expires:</span>
          {{ optional($session->expires_at)->timezone(config('app.timezone'))->format('Y-m-d h:i A') ?? '—' }}
          <span class="mx-2 text-white/50">•</span>
          <span class="font-semibold text-white">Status:</span>
          {{-- ✅ no bg card for running/ended, just text --}}
          @if($session->ended_at)
            <span class="font-extrabold" style="color: {{ $cmYellow }};">Ended</span>
          @else
            <span class="font-extrabold text-white">Running</span>
          @endif
        </p>
      </div>

      <div class="flex flex-wrap items-center gap-2">
        <a href="{{ route('attendance.index') }}" class="cm-btn-ghost">
          Back
        </a>

        @if(!$session->ended_at)
          <form method="POST" action="{{ route('attendance.sessions.end', $session) }}">
            @csrf
            <button type="submit" class="cm-btn-end">
              End Session
            </button>
          </form>
        @endif
      </div>
    </div>

    @if(session('success'))
      <div class="mt-4 rounded-2xl border px-4 py-3 font-semibold"
           style="border-color: rgba(139,222,99,.35); background: rgba(139,222,99,.85); color: #0B1B0F;">
        {{ session('success') }}
      </div>
    @endif

    {{-- Content --}}
    <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-5 items-stretch">

      {{-- Left: QR / code --}}
      <section class="cm-card cm-card-h">
        <div class="flex items-start justify-between gap-4">
          <div>
            <h2 class="text-xl font-extrabold">QR Code</h2>
            <p class="text-sm text-slate-600">Students can scan or type the code.</p>
          </div>

          {{-- softer tint (green/yellow) instead of blue --}}
          <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-extrabold border"
                style="border-color: rgba(139,222,99,.35); background: rgba(139,222,99,.14); color: rgb(15 23 42);">
            <span class="w-2 h-2 rounded-full" style="background: {{ $cmGreen }};"></span>
            Share
          </span>
        </div>

        <div class="mt-5 cm-card-inner" style="
          background:
            linear-gradient(135deg, rgba(36,99,235,.06) 0%, rgba(139,222,99,.10) 60%, rgba(237,183,10,.08) 100%);
          border-color: rgba(36,99,235,.14);
        ">
          <p class="text-xs font-extrabold uppercase tracking-wider text-slate-600">Session Code</p>

          <div class="mt-2 flex flex-wrap items-center gap-3">
            <div class="px-4 py-2 rounded-2xl border font-extrabold tracking-[0.35em] text-lg bg-white/95"
                 style="border-color: rgba(36,99,235,.14); color: rgb(15 23 42);">
              {{ $session->session_code }}
            </div>

            {{-- primary button keeps blue, but overall page less blue --}}
            <button type="button" id="copyBtn" class="cm-btn-primary">
              Copy
            </button>
          </div>

          <div class="mt-5 rounded-2xl border p-4 grid place-items-center bg-white/95"
               style="border-color: rgba(36,99,235,.14);">
            <div id="qr"></div>
          </div>

          <p class="mt-4 text-xs text-slate-600">
            If QR doesn’t show, your internet might be blocked (CDN). In that case, use the code.
          </p>
        </div>

        {{-- ✅ remove bottom extra cards (Auto refresh / Tip) --}}
      </section>

      {{-- Right: Check-ins --}}
      <section class="lg:col-span-2 cm-card cm-card-h flex flex-col">
        <div class="flex items-start justify-between gap-4">
          <div>
            <h2 class="text-xl font-extrabold">Check-ins</h2>
            <p class="text-sm text-slate-600">Live updates (auto refresh).</p>
          </div>

          {{-- softer tint total card (green) --}}
          <div class="rounded-2xl border px-5 py-4"
               style="border-color: rgba(139,222,99,.35); background: rgba(139,222,99,.18);">
            <p class="text-xs font-extrabold text-slate-700">Total</p>
            <p class="mt-1 text-2xl font-extrabold text-slate-900" id="liveCount">{{ $count }}</p>
          </div>
        </div>

        <div class="mt-5 cm-card-inner flex-1 min-h-0 flex flex-col overflow-hidden"
             style="background: rgba(36,99,235,.05); border-color: rgba(36,99,235,.14);">
          <div class="overflow-x-auto flex-1 min-h-0 pr-1 cm-scroll">
            <table class="min-w-full text-sm">
              <thead>
                <tr class="text-left">
                  <th class="py-2 pr-4 text-slate-600 font-extrabold">Student</th>
                  <th class="py-2 pr-4 text-slate-600 font-extrabold">Email</th>
                  <th class="py-2 pr-4 text-slate-600 font-extrabold">Marked At</th>
                </tr>
              </thead>

              <tbody class="divide-y" style="--tw-divide-opacity: 1; border-color: rgba(36,99,235,.10);">
                @forelse($records as $r)
                  <tr class="hover:bg-white/60 transition">
                    <td class="py-3 pr-4">
                      <div class="font-extrabold text-slate-900">{{ $r->student?->name ?? 'Student' }}</div>
                      <div class="text-xs font-semibold text-slate-600">Checked in</div>
                    </td>

                    <td class="py-3 pr-4 text-slate-700 font-semibold">
                      {{ $r->student?->email ?? '' }}
                    </td>

                    <td class="py-3 pr-4">
                      <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-extrabold border"
                            style="border-color: rgba(237,183,10,.30); background: rgba(237,183,10,.12); color: rgb(15 23 42);">
                        {{ optional($r->marked_at)->timezone(config('app.timezone'))->format('Y-m-d h:i A') ?? '—' }}
                      </span>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="3" class="py-8 text-slate-700 font-semibold">
                      No check-ins yet.
                      <span class="text-slate-600 font-normal">Share the code or QR with students.</span>
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <div class="mt-4">
            {{ $records->links() }}
          </div>
        </div>
      </section>
    </div>

    <style>
      .cm-card{
        background: rgba(255,255,255,.92);
        border: 1px solid rgba(255,255,255,.35);
        border-radius: 1.5rem;
        padding: 1.5rem;
        color: rgb(15 23 42);
        box-shadow: 0 18px 40px rgba(15, 23, 42, .14);
      }

      .cm-card-h{
        height: 520px;
        display: flex;
        flex-direction: column;
      }

      .cm-card-inner{
        background: rgba(36, 99, 235, .06);
        border-radius: 1.25rem;
        padding: 1.25rem;
        border: 1px solid rgba(36, 99, 235, .18);
      }

      .cm-btn-primary{
        padding: .60rem 1.05rem;
        border-radius: 9999px;
        font-weight: 950;
        color: #fff;
        background: {{ $cmBlue }};
        box-shadow: 0 10px 26px rgba(36,99,235,.28);
        transition: transform .15s ease, box-shadow .2s ease;
        white-space: nowrap;
      }
      .cm-btn-primary:hover{
        transform: translateY(-1px);
        box-shadow: 0 16px 34px rgba(36,99,235,.36);
      }

      .cm-btn-ghost{
        padding: .60rem 1.05rem;
        border-radius: 9999px;
        font-weight: 950;
        background: rgba(255,255,255,.95);
        border: 1px solid rgba(255,255,255,.55);
        color: rgb(15 23 42) !important;
        box-shadow: 0 10px 22px rgba(15, 23, 42, .12);
        transition: transform .15s ease, box-shadow .2s ease;
        white-space: nowrap;
      }
      .cm-btn-ghost:hover{
        transform: translateY(-1px);
        box-shadow: 0 16px 30px rgba(15, 23, 42, .16);
      }

      .cm-btn-end{
        padding: .60rem 1.05rem;
        border-radius: 9999px;
        font-weight: 950;
        background: {{ $cmRed }};
        color: #fff;
        border: none;
        box-shadow: 0 10px 26px rgba(239, 68, 68, .35);
        transition: transform .15s ease, box-shadow .2s ease;
        white-space: nowrap;
      }
      .cm-btn-end:hover{
        transform: translateY(-1px);
        box-shadow: 0 16px 34px rgba(239, 68, 68, .45);
      }

      /* scrollbars */
      .cm-scroll::-webkit-scrollbar { width: 8px; height: 8px; }
      .cm-scroll::-webkit-scrollbar-track { background: rgba(15, 23, 42, .06); border-radius: 9999px; }
      .cm-scroll::-webkit-scrollbar-thumb { background: rgba(15, 23, 42, .18); border-radius: 9999px; }
      .cm-scroll::-webkit-scrollbar-thumb:hover { background: rgba(15, 23, 42, .26); }

      /* pagination inside light cards */
      .pagination * { color: rgb(15 23 42) !important; }
      .pagination .disabled span { opacity: .55; }

      @media (max-width: 1024px){
        .cm-card-h{ height:auto; }
      }
    </style>

  </div>
</div>

{{-- QR Code generator (CDN) --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
  (function () {
    const code = @json($session->session_code);

    // QR
    const qrEl = document.getElementById('qr');
    if (qrEl && window.QRCode) {
      qrEl.innerHTML = '';
      new QRCode(qrEl, { text: code, width: 190, height: 190 });
    }

    // Copy
    const copyBtn = document.getElementById('copyBtn');
    if (copyBtn) {
      copyBtn.addEventListener('click', async () => {
        try {
          await navigator.clipboard.writeText(code);
          const old = copyBtn.textContent;
          copyBtn.textContent = 'Copied!';
          setTimeout(() => copyBtn.textContent = old, 1200);
        } catch (e) {}
      });
    }

    // Live polling count (optional)
    @if(!$session->ended_at)
      const liveUrl = @json(route('attendance.sessions.live', $session));
      async function poll(){
        try{
          const res = await fetch(liveUrl, { headers: { "Accept": "application/json" }});
          const data = await res.json();
          const el = document.getElementById('liveCount');
          if(el) el.textContent = data.count ?? 0;
        }catch(e){}
      }
      poll();
      setInterval(poll, 4000);
    @endif
  })();
</script>
@endsection
