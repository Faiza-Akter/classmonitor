@extends('layouts.app')

@section('content')
@php
  $cmBlue='#2463EB'; $cmGreen='#8BDE63'; $cmYellow='#EDB70A';
@endphp

<div class="min-h-[calc(100vh-88px)] bg-white text-slate-900">
  <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">

    <div class="flex items-start justify-between gap-4">
      <div>
        <p class="text-sm font-semibold text-slate-600">Leaderboard</p>
        <h1 class="mt-1 text-3xl font-extrabold">{{ $quiz->title }}</h1>
        <p class="mt-1 text-sm text-slate-600">Ranked by score (tie-break: earlier submit).</p>
      </div>

      <a href="{{ route('quizzes.manage', $quiz) }}"
         class="px-4 py-2 rounded-xl font-semibold border border-slate-200 bg-white hover:shadow-sm transition">
        Back to Manage
      </a>
    </div>

    <div class="mt-6 rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
      <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
        <p class="font-extrabold">Top Results</p>
        <p class="text-sm text-slate-600">Max Score: <span class="font-semibold">{{ $maxScore }}</span></p>
      </div>

      <div class="divide-y divide-slate-200">
        @forelse($attempts as $i => $a)
          <div class="px-5 py-4 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3 min-w-0">
              <div class="w-9 h-9 rounded-xl grid place-items-center text-white font-extrabold"
                   style="background: {{ $cmBlue }};">
                {{ $i+1 }}
              </div>
              <div class="min-w-0">
                <p class="font-bold truncate">{{ $a->student?->name ?? 'Student' }}</p>
                <p class="text-xs text-slate-500 truncate">{{ $a->student?->email ?? '' }}</p>
              </div>
            </div>

            <div class="text-right">
              <p class="text-lg font-extrabold">{{ $a->score }} / {{ $maxScore }}</p>
              <p class="text-xs text-slate-500">{{ optional($a->submitted_at)->format('Y-m-d h:i A') }}</p>
            </div>
          </div>
        @empty
          <div class="px-5 py-6 text-slate-600">No submissions yet.</div>
        @endforelse
      </div>
    </div>

  </div>
</div>
@endsection
