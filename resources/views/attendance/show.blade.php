@extends('layouts.app')

@section('content')
@php
  $cmBlue='#2463EB'; $cmGreen='#8BDE63'; $cmYellow='#EDB70A';
@endphp

<div class="min-h-[calc(100vh-88px)] bg-white text-slate-900">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">

    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
      <div>
        <p class="text-sm font-semibold text-slate-600">Attendance Session</p>
        <h1 class="mt-1 text-3xl font-extrabold">Session: {{ $session->session_code }}</h1>
        <p class="mt-1 text-sm text-slate-600">
          Starts: {{ optional($session->starts_at)->timezone(config('app.timezone'))->format('Y-m-d h:i A') ?? '—' }}
          • Expires: {{ optional($session->expires_at)->timezone(config('app.timezone'))->format('Y-m-d h:i A') ?? '—' }}
          • Status: <span class="font-semibold">{{ $session->ended_at ? 'Ended' : 'Running' }}</span>
        </p>
      </div>

      <div class="flex flex-wrap gap-2">
        <a href="{{ route('attendance.index') }}"
           class="px-4 py-2 rounded-xl font-semibold border border-slate-200 bg-white hover:shadow-sm transition">
          Back
        </a>

        @if(!$session->ended_at)
          <form method="POST" action="{{ route('attendance.sessions.end', $session) }}">
            @csrf
            <button type="submit"
                    class="px-4 py-2 rounded-xl font-semibold border border-slate-200 bg-white hover:shadow-sm transition">
              End Session
            </button>
          </form>
        @endif
      </div>
    </div>

    @if(session('success'))
      <div class="mt-5 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-green-800">
        {{ session('success') }}
      </div>
    @endif

    <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-4">

      {{-- QR + Code card --}}
      <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-6">
        <h2 class="text-lg font-extrabold">QR Code</h2>
        <p class="text-sm text-slate-600 mt-1">Students can scan or type the code.</p>

        <div class="mt-4 flex items-center gap-3">
          <div class="px-4 py-2 rounded-xl border border-slate-200 bg-slate-50 font-extrabold tracking-[0.25em] text-lg">
            {{ $session->session_code }}
          </div>

          <button type="button" id="copyBtn"
                  class="px-4 py-2 rounded-xl font-semibold text-white shadow-sm hover:shadow-md transition"
                  style="background: {{ $cmBlue }};">
            Copy
          </button>
        </div>

        <div class="mt-5 rounded-2xl border border-slate-200 bg-white p-4 grid place-items-center">
          <div id="qr"></div>
        </div>

        <p class="mt-4 text-xs text-slate-500">
          If QR doesn’t show, your internet might be blocked (CDN). In that case, use the code.
        </p>
      </div>

      {{-- Live count --}}
      <div class="lg:col-span-2 rounded-2xl border border-slate-200 bg-white shadow-sm p-6">
        <div class="flex items-center justify-between">
          <div>
            <h2 class="text-lg font-extrabold">Check-ins</h2>
            <p class="text-sm text-slate-600">Live updates (auto refresh).</p>
          </div>

          <div class="px-4 py-2 rounded-2xl border border-slate-200 bg-slate-50">
            <p class="text-xs font-bold text-slate-500">Total</p>
            <p class="text-2xl font-extrabold" id="liveCount">{{ $count }}</p>
          </div>
        </div>

        <div class="mt-5 overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead>
              <tr class="text-left text-slate-600">
                <th class="py-2 pr-4">Student</th>
                <th class="py-2 pr-4">Email</th>
                <th class="py-2 pr-4">Marked At</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              @forelse($records as $r)
                <tr>
                  <td class="py-3 pr-4 font-semibold">{{ $r->student?->name ?? 'Student' }}</td>
                  <td class="py-3 pr-4 text-slate-600">{{ $r->student?->email ?? '' }}</td>
                  <td class="py-3 pr-4 text-slate-600">
                    {{ optional($r->marked_at)->timezone(config('app.timezone'))->format('Y-m-d h:i A') ?? '—' }}
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="3" class="py-6 text-slate-600">No check-ins yet.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <div class="mt-4">
          {{ $records->links() }}
        </div>
      </div>

    </div>
  </div>
</div>

{{-- QR Code generator (CDN) --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
  (function () {
    // QR
    const code = @json($session->session_code);
    const qrEl = document.getElementById('qr');
    if (qrEl && window.QRCode) {
      qrEl.innerHTML = '';
      new QRCode(qrEl, {
        text: code,
        width: 180,
        height: 180
      });
    }

    // Copy
    const copyBtn = document.getElementById('copyBtn');
    if (copyBtn) {
      copyBtn.addEventListener('click', async () => {
        try {
          await navigator.clipboard.writeText(code);
          copyBtn.textContent = 'Copied!';
          setTimeout(() => copyBtn.textContent = 'Copy', 1200);
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
