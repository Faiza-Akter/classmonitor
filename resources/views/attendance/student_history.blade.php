@extends('layouts.app')

@section('content')
@php
  $cmBlue='#2463EB'; $cmGreen='#8BDE63'; $cmYellow='#EDB70A';
@endphp

<div class="min-h-[calc(100vh-88px)] bg-white text-slate-900">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">

    <div class="flex items-start justify-between gap-4">
      <div>
        <p class="text-sm font-semibold text-slate-600">Attendance</p>
        <h1 class="mt-1 text-3xl font-extrabold">Your Attendance History</h1>
        <p class="mt-1 text-sm text-slate-600">All your marked sessions are listed here.</p>
      </div>

      <a href="{{ route('attendance.join.form') }}"
         class="px-4 py-2 rounded-xl font-semibold shadow-sm hover:shadow-md transition"
         style="background:{{ $cmGreen }}; color:#0B1B0F;">
        + Join Attendance
      </a>
    </div>

    <div class="mt-6 rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full">
          <thead class="bg-slate-50">
            <tr class="text-left text-xs font-bold uppercase tracking-wider text-slate-500">
              <th class="px-5 py-4">Session Code</th>
              <th class="px-5 py-4">Teacher</th>
              <th class="px-5 py-4">Marked Time</th>
              <th class="px-5 py-4">Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-200">
            @forelse($rows as $r)
              @php
                $s = $r->session;
                $status = 'Ended';
                $bg = 'rgba(148,163,184,.18)';
                $fg = '#0f172a';

                if($s && !$s->ended_at && (!$s->expires_at || $s->expires_at->isFuture())){
                  $status = 'Active';
                  $bg = 'rgba(139,222,99,.20)';
                  $fg = '#0B1B0F';
                } elseif($s && $s->expires_at && $s->expires_at->isPast() && !$s->ended_at) {
                  $status = 'Expired';
                  $bg = 'rgba(237,183,10,.20)';
                  $fg = '#3a2b00';
                }
              @endphp

              <tr>
                <td class="px-5 py-4 font-extrabold tracking-[0.18em]">
                  {{ $s?->session_code ?? '—' }}
                </td>
                <td class="px-5 py-4 text-sm text-slate-700">
                  {{ $s?->teacher?->name ?? 'Teacher' }}
                </td>
                <td class="px-5 py-4 text-sm text-slate-700">
                  {{ optional($r->marked_at)?->timezone('Asia/Dhaka')->format('Y-m-d h:i A') }}
                </td>
                <td class="px-5 py-4">
                  <span class="text-xs font-bold px-2 py-1 rounded-lg"
                        style="background:{{ $bg }}; color:{{ $fg }};">
                    {{ $status }}
                  </span>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="px-5 py-8 text-center text-sm text-slate-600">
                  No attendance found yet.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div class="mt-4">
      {{ $rows->links() }}
    </div>

  </div>
</div>
@endsection
