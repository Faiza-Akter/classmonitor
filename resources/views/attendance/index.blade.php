@extends('layouts.app')

@section('content')
@php
    $cmBlue   = '#2463EB';
    $cmGreen  = '#8BDE63';
    $cmYellow = '#EDB70A';
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
        <div>
            <p class="text-sm font-semibold text-slate-600">Attendance</p>
            <h1 class="mt-1 text-2xl sm:text-3xl font-extrabold text-slate-900">Sessions</h1>
            <p class="mt-1 text-sm text-slate-600">Create a session code, students join, you monitor live.</p>
        </div>

        <form method="POST" action="{{ route('attendance.sessions.create') }}" class="flex items-center gap-2">
            @csrf
            <label class="text-sm font-semibold text-slate-700">Expires</label>
            <select name="expires_minutes"
                    class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold">
                <option value="5">5 min</option>
                <option value="10" selected>10 min</option>
                <option value="15">15 min</option>
                <option value="30">30 min</option>
            </select>

            <button type="submit"
                    class="px-4 py-2 rounded-xl font-semibold shadow-sm hover:shadow-md transition"
                    style="background: {{ $cmGreen }}; color:#0B1B0F;">
                + New Session
            </button>
        </form>
    </div>

    @if (session('success'))
        <div class="mt-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-900">
            {{ session('success') }}
        </div>
    @endif

    <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-5">
        {{-- Active session --}}
        <div class="lg:col-span-2 rounded-3xl border border-slate-200 bg-white shadow-sm p-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-xl font-extrabold text-slate-900">Active Session</h2>
                    <p class="text-sm text-slate-600">Students will join using this code.</p>
                </div>

                @if($activeSession)
                    <form method="POST" action="{{ route('attendance.sessions.end', $activeSession) }}">
                        @csrf
                        <button type="submit"
                                class="px-4 py-2 rounded-xl font-semibold border border-slate-200 bg-white text-slate-900 shadow-sm hover:shadow-md transition">
                            End Session
                        </button>
                    </form>
                @endif
            </div>

            <div class="mt-5 rounded-2xl border border-slate-200 bg-slate-50 p-6">
                @if($activeSession)
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Session Code</p>
                    <div class="mt-2 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <div class="text-3xl font-extrabold tracking-[0.25em] text-slate-900">
                                {{ $activeSession->session_code }}
                            </div>
                            <p class="mt-2 text-sm text-slate-600">
                                Expires at:
                                <span class="font-semibold">{{ optional($activeSession->expires_at)->format('h:i A') }}</span>
                            </p>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-white px-5 py-4">
                            <p class="text-xs font-bold text-slate-500">Live Check-ins</p>
                            <p id="liveCount" class="mt-1 text-2xl font-extrabold text-slate-900">0</p>
                        </div>
                    </div>

                    <div class="mt-5">
                        <p class="text-sm font-bold text-slate-900">Latest check-ins</p>
                        <div id="latestList" class="mt-3 space-y-2">
                            <p class="text-sm text-slate-500">Waiting for students…</p>
                        </div>
                    </div>
                @else
                    <p class="text-sm text-slate-600">No active session. Create one to start attendance.</p>
                @endif
            </div>
        </div>

        {{-- Recent sessions --}}
        <div class="rounded-3xl border border-slate-200 bg-white shadow-sm p-6">
            <h2 class="text-xl font-extrabold text-slate-900">Recent</h2>
            <p class="text-sm text-slate-600">Your latest sessions.</p>

            <div class="mt-4 space-y-3">
                @forelse($recentSessions as $s)
                    <div class="rounded-2xl border border-slate-200 bg-white p-4">
                        <div class="flex items-center justify-between">
                            <div class="font-extrabold text-slate-900 tracking-widest">{{ $s->session_code }}</div>
                            <div class="text-xs font-semibold text-slate-500">
                                {{ $s->created_at->format('d M, h:i A') }}
                            </div>
                        </div>
                        <div class="mt-2 text-xs text-slate-500">
                            Expires: {{ optional($s->expires_at)->format('h:i A') ?? '—' }}
                            • Ended: {{ $s->ended_at ? $s->ended_at->format('h:i A') : 'Active/Running' }}
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">No sessions yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@if($activeSession)
<script>
    const liveUrl = @json(route('attendance.sessions.live', $activeSession));

    async function pollLive() {
        try {
            const res = await fetch(liveUrl, { headers: { "Accept": "application/json" }});
            const data = await res.json();

            document.getElementById('liveCount').textContent = data.count ?? 0;

            const list = document.getElementById('latestList');
            if (!data.latest || data.latest.length === 0) {
                list.innerHTML = `<p class="text-sm text-slate-500">Waiting for students…</p>`;
                return;
            }

            list.innerHTML = data.latest.map(item => `
                <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-white px-4 py-3">
                    <div>
                        <div class="font-bold text-slate-900">${item.name}</div>
                        <div class="text-xs text-slate-500">${item.email ?? ''}</div>
                    </div>
                    <div class="text-xs font-semibold text-slate-500">${item.time ?? ''}</div>
                </div>
            `).join('');
        } catch (e) {
            // ignore
        }
    }

    pollLive();
    setInterval(pollLive, 3000);
</script>
@endif
@endsection
