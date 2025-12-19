@extends('layouts.app')

@section('content')
@php
    $cmBlue = '#2463EB';
    $cmGreen = '#8BDE63';
    $cmYellow = '#EDB70A';
    $cmRed = '#EF4444';
@endphp

<div class="min-h-[calc(100vh-88px)] text-white" style="
    background:
      radial-gradient(900px 500px at 20% 8%, rgba(255,255,255,.18) 0%, rgba(255,255,255,0) 60%),
      radial-gradient(700px 420px at 85% 30%, rgba(139,222,99,.18) 0%, rgba(255,255,255,0) 65%),
      linear-gradient(135deg, rgba(36,99,235,.96) 0%, rgba(36,99,235,.92) 55%, rgba(29,78,216,.96) 100%);
">

    {{-- ✅ ADD: Bottom border/strip under navbar (like Quizzes page) --}}
    <div class="h-[6px] w-full"
         style="background: linear-gradient(90deg, {{ $cmBlue }}, {{ $cmGreen }}, {{ $cmYellow }});"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <h1 class="mt-1 text-3xl sm:text-4xl font-extrabold text-white">Attendance Sessions? Sessions</h1>
                <p class="mt-1 text-sm text-white/80">
                    Create a session code, students join, you monitor live.
                </p>
            </div>

            <form method="POST" action="{{ route('attendance.sessions.create') }}"
                  class="flex flex-wrap items-center gap-2">
                @csrf
                <label class="text-sm font-semibold text-white/90">Expires</label>
                <select name="expires_minutes" class="cm-select">
                    <option value="5">5 min</option>
                    <option value="10" selected>10 min</option>
                    <option value="15">15 min</option>
                    <option value="30">30 min</option>
                </select>

                <button type="submit" class="cm-btn-new">
                    + New Session
                </button>
            </form>
        </div>

        @if (session('success'))
            <div class="mt-4 rounded-2xl border px-4 py-3 text-emerald-950 font-semibold"
                 style="border-color: rgba(139,222,99,.35); background: rgba(139,222,99,.85);">
                {{ session('success') }}
            </div>
        @endif

        <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-5 items-stretch">

            {{-- Left: Active session --}}
            <section class="lg:col-span-2 cm-card cm-card-h">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-extrabold">Active Session</h2>
                        <p class="text-sm text-slate-600">Students will join using this code.</p>
                    </div>

                    @if($activeSession)
                        <form method="POST" action="{{ route('attendance.sessions.end', $activeSession) }}">
                            @csrf
                            <button type="submit" class="cm-btn-end">
                                End Session
                            </button>
                        </form>
                    @endif
                </div>

                <div class="mt-5 cm-card-inner flex-1 flex flex-col overflow-hidden">
                    @if($activeSession)
                        <p class="text-xs font-extrabold uppercase tracking-wider text-slate-600">
                            Session Code
                        </p>

                        <div class="mt-2 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div>
                                <div class="text-3xl font-extrabold tracking-[0.25em] text-slate-900">
                                    {{ $activeSession->session_code }}
                                </div>
                                <p class="mt-2 text-sm text-slate-700">
                                    Expires at:
                                    <span class="font-extrabold">
                                        {{ optional($activeSession->expires_at)->format('h:i A') }}
                                    </span>
                                </p>
                            </div>

                            <div class="rounded-2xl border px-5 py-4"
                                 style="border-color: rgba(139,222,99,.35); background: rgba(139,222,99,.22);">
                                <p class="text-xs font-extrabold text-slate-700">Live Check-ins</p>
                                <p id="liveCount" class="mt-1 text-2xl font-extrabold text-slate-900">0</p>
                            </div>
                        </div>

                        {{-- Placeholder (hidden when students exist) --}}
                        <div id="livePlaceholder" class="mt-6">
                            <div class="w-full rounded-2xl border p-6"
                                 style="border-color: rgba(36,99,235,.18); background: rgba(255,255,255,.70);">
                                <div class="flex flex-col sm:flex-row items-center gap-6">
                                    <div class="shrink-0 w-24 h-24 rounded-3xl flex items-center justify-center"
                                         style="background: linear-gradient(135deg, rgba(36,99,235,.16), rgba(139,222,99,.16)); border: 1px solid rgba(36,99,235,.18);">
                                        <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="#2463EB"
                                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                             aria-hidden="true">
                                            <path d="M17 20h-1a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4h1" />
                                            <circle cx="9" cy="8" r="3" />
                                            <path d="M23 11l-2 2-1-1" />
                                            <path d="M16 6.5a3 3 0 0 1 0 5.5" />
                                        </svg>
                                    </div>

                                    <div class="min-w-0 text-center sm:text-left">
                                        <p class="text-sm font-extrabold text-slate-900">Share the code with your class</p>
                                        <p class="mt-2 text-sm text-slate-700">
                                            Students can join now. As they check in, the list will update automatically
                                            below.
                                        </p>

                                        <div class="mt-4 flex flex-wrap items-center justify-center sm:justify-start gap-2 text-xs font-semibold text-slate-700">
                                            <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 border"
                                                  style="border-color: rgba(36,99,235,.18); background: rgba(36,99,235,.06);">
                                                <span class="w-2 h-2 rounded-full" style="background: {{ $cmBlue }};"></span>
                                                Live updates
                                            </span>
                                            <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 border"
                                                  style="border-color: rgba(139,222,99,.25); background: rgba(139,222,99,.12);">
                                                <span class="w-2 h-2 rounded-full" style="background: {{ $cmGreen }};"></span>
                                                Auto refresh
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Attendance list (scroll) --}}
                        <div class="mt-4 flex-1 min-h-0 overflow-auto pr-1 cm-scroll" id="latestList"></div>
                    @else
                        <div class="flex-1 flex items-center justify-center text-slate-600">
                            No active session.
                        </div>
                    @endif
                </div>
            </section>

            {{-- Right: Recent sessions --}}
            <aside class="cm-card cm-card-h lg:sticky lg:top-24 flex flex-col">
                <h2 class="text-xl font-extrabold">Recent</h2>
                <p class="text-sm text-slate-600">Your latest sessions.</p>

                <div class="mt-4 space-y-3 overflow-auto pr-1 cm-scroll flex-1 min-h-0">
                    @forelse($recentSessions as $s)
                        <div class="rounded-2xl border p-4"
                             style="border-color: rgba(237,183,10,.30); background: rgba(237,183,10,.14);">
                            <div class="flex justify-between">
                                <div class="font-extrabold text-slate-900 tracking-widest">
                                    {{ $s->session_code }}
                                </div>
                                <div class="text-xs text-slate-700">
                                    {{ $s->created_at->format('d M, h:i A') }}
                                </div>
                            </div>
                            <div class="mt-2 text-xs text-slate-700 font-semibold">
                                Expires: {{ optional($s->expires_at)->format('h:i A') ?? '—' }}
                                • Ended: {{ $s->ended_at ? $s->ended_at->format('h:i A') : 'Active/Running' }}
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-200/80">No sessions yet.</p>
                    @endforelse
                </div>
            </aside>
        </div>
    </div>

    <style>
        .cm-card{
            background: rgba(255,255,255,.92);
            border: 1px solid rgba(255,255,255,.35);
            border-radius: 1.5rem;
            padding: 1.5rem;
            color: rgb(15 23 42);
        }

        .cm-card-h{ height: 520px; display:flex; flex-direction:column; }

        .cm-card-inner{
            background: rgba(36,99,235,.06);
            border-radius: 1.25rem;
            padding: 1.25rem;
            border: 1px solid rgba(36,99,235,.18);
        }

        .cm-btn-new{
            padding:.55rem 1rem;
            border-radius:9999px;
            font-weight:950;
            background: {{ $cmGreen }};
            color:#0B1B0F;
            box-shadow:0 10px 26px rgba(15,23,42,.18);
            transition:transform .15s ease, box-shadow .2s ease;
            white-space:nowrap;
        }
        .cm-btn-new:hover{ transform:translateY(-1px); box-shadow:0 16px 34px rgba(15,23,42,.24); }

        .cm-select{
            border-radius:9999px;
            border:1px solid rgba(226,232,240,1);
            background:rgba(255,255,255,.95);
            padding:.55rem .9rem;
            font-size:.875rem;
            font-weight:900;
            color:rgb(15 23 42);
            box-shadow:0 8px 20px rgba(15,23,42,.10);
            outline:none;
        }
        .cm-select:focus{
            box-shadow:0 0 0 4px rgba(255,255,255,.20);
            border-color:rgba(255,255,255,.55);
        }

        .cm-btn-end{
            padding:.55rem .95rem;
            border-radius:9999px;
            font-weight:950;
            background: {{ $cmRed }};
            color:#fff;
            border:none;
            box-shadow:0 10px 26px rgba(239,68,68,.35);
            transition:transform .15s ease, box-shadow .2s ease;
            white-space:nowrap;
        }
        .cm-btn-end:hover{ transform:translateY(-1px); box-shadow:0 16px 34px rgba(239,68,68,.45); }

        .cm-scroll::-webkit-scrollbar{ width:8px; }
        .cm-scroll::-webkit-scrollbar-track{ background: rgba(15,23,42,.06); border-radius:9999px; }
        .cm-scroll::-webkit-scrollbar-thumb{ background: rgba(15,23,42,.18); border-radius:9999px; }
        .cm-scroll::-webkit-scrollbar-thumb:hover{ background: rgba(15,23,42,.26); }

        @media (max-width: 1024px){
            .cm-card-h{ height:auto; }
            aside.lg\:sticky{ position: static !important; }
        }
    </style>

</div>

@if($activeSession)
<script>
  const liveUrl = @json(route('attendance.sessions.live', $activeSession));

  async function pollLive() {
    try {
      const res = await fetch(liveUrl, { headers: { "Accept": "application/json" } });
      const data = await res.json();

      document.getElementById('liveCount').textContent = data.count ?? 0;

      const list = document.getElementById('latestList');
      const placeholder = document.getElementById('livePlaceholder');

      if (!data.latest || data.latest.length === 0) {
        if (placeholder) placeholder.style.display = "";
        list.innerHTML = "";
        return;
      }

      if (placeholder) placeholder.style.display = "none";

      list.innerHTML = data.latest.map(item => `
        <div class="flex items-center justify-between rounded-xl border px-4 py-3 mb-2"
             style="border-color: rgba(36,99,235,.18); background: rgba(255,255,255,.92);">
          <div>
            <div class="font-extrabold text-slate-900">${item.name}</div>
            <div class="text-xs text-slate-600">${item.email ?? ''}</div>
          </div>
          <div class="text-xs font-semibold text-slate-700">${item.time ?? ''}</div>
        </div>
      `).join('');
    } catch (e) {}
  }

  pollLive();
  setInterval(pollLive, 3000);
</script>
@endif
@endsection
