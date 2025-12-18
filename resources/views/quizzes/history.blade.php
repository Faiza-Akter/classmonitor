@extends('layouts.app')

@section('content')
@php $cmBlue='#2463EB'; $cmGreen='#8BDE63'; $cmYellow='#EDB70A'; @endphp

<div class="min-h-[calc(100vh-88px)] bg-white text-slate-900">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">

    <div class="flex items-start justify-between gap-4">
      <div>
        <p class="text-sm font-semibold text-slate-600">Quizzes</p>
        <h1 class="mt-1 text-3xl font-extrabold">Your Quiz History</h1>
        <p class="mt-1 text-sm text-slate-600">Submitted attempts and scores.</p>
      </div>
      <a href="{{ route('student.dashboard') }}"
         class="px-4 py-2 rounded-xl font-semibold border border-slate-200 bg-white hover:shadow-sm transition">
        Back
      </a>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-3">
      @forelse($attempts as $a)
        @php
          $quiz = $a->quiz;
          $teacher = $quiz?->teacher?->name ?? 'Teacher';
          $submitted = optional($a->submitted_at)?->timezone('Asia/Dhaka')->format('Y-m-d h:i A');
          $scoreText = is_null($a->submitted_at) ? 'In progress' : ($a->score . ' / ' . ($a->max_score ?? 0));
        @endphp

        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5">
          <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
              <p class="text-sm font-extrabold text-slate-900">{{ $quiz?->title ?? 'Quiz' }}</p>
              <p class="mt-1 text-xs text-slate-600">Teacher: {{ $teacher }}</p>
              <p class="mt-1 text-xs text-slate-600">Submitted: {{ $submitted ?? '—' }}</p>
            </div>

            <div class="flex items-center gap-3">
              <div class="px-4 py-2 rounded-xl border border-slate-200 bg-slate-50">
                <p class="text-xs font-bold text-slate-500">Score</p>
                <p class="text-sm font-extrabold" style="color:{{ $cmBlue }};">{{ $scoreText }}</p>
              </div>

              <a href="{{ route('student.quizzes.show', $a->id) }}"
                 class="px-4 py-2 rounded-xl font-semibold text-white shadow-sm hover:shadow-md transition"
                 style="background:{{ $cmBlue }};">
                View Details
              </a>
            </div>
          </div>
        </div>
      @empty
        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6 text-sm text-slate-600">
          No quiz attempts yet.
        </div>
      @endforelse
    </div>

  </div>
</div>
@endsection
