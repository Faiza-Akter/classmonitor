@extends('layouts.app')

@section('content')
@php $cmBlue='#2463EB'; $cmGreen='#8BDE63'; $cmYellow='#EDB70A'; @endphp

<div class="min-h-[calc(100vh-88px)] bg-white text-slate-900">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-10">

    <p class="text-sm font-semibold text-slate-600">Attempt Details</p>
    <h1 class="mt-1 text-3xl font-extrabold">{{ $attempt->quiz?->title ?? 'Quiz' }}</h1>
    <p class="mt-1 text-sm text-slate-600">
      Teacher: <span class="font-semibold">{{ $attempt->quiz?->teacher?->name ?? 'Teacher' }}</span>
    </p>

    <div class="mt-6 rounded-2xl border border-slate-200 bg-white shadow-sm p-6">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
          <p class="text-sm text-slate-600">Score</p>
          <p class="mt-1 text-4xl font-extrabold" style="color:{{ $cmBlue }};">
            {{ (int)$attempt->score }} <span class="text-base text-slate-500 font-bold">/ {{ (int)$maxScore }}</span>
          </p>
        </div>
        <div class="text-sm text-slate-600">
          Submitted:
          <span class="font-semibold">
            {{ optional($attempt->submitted_at)?->timezone('Asia/Dhaka')->format('Y-m-d h:i A') ?? '—' }}
          </span>
        </div>
      </div>
    </div>

    <div class="mt-6 space-y-4">
      @foreach($attempt->answers as $ans)
        @php
          $q = $ans->question;
          $type = $q->type ?? 'mcq';
          $correctOpt = $correctByQuestion[$q->id] ?? null;

          // status badge
          $label = 'Wrong';
          $bg = 'rgba(239,68,68,.15)'; $fg = '#7f1d1d';

          if ($type === 'short' && $ans->is_correct === null) {
            $label = 'Pending Review';
            $bg = 'rgba(237,183,10,.18)'; $fg = '#3a2b00';
          } elseif ($ans->is_correct === true) {
            $label = 'Correct';
            $bg = 'rgba(139,222,99,.22)'; $fg = '#0B1B0F';
          }

          // points earned
          $pointsEarned = '0';
          if ($type === 'short' && $ans->is_correct === null) {
            $pointsEarned = '—';
          } elseif ($ans->is_correct === true) {
            $pointsEarned = (string)($q->points ?? 0);
          }
        @endphp

        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5">
          <div class="flex items-start justify-between gap-3">
            <div>
              <p class="font-extrabold">{{ $q->text }}</p>
              <p class="text-sm text-slate-600 mt-1">
                Points: {{ $q->points }} • Earned: <span class="font-semibold">{{ $pointsEarned }}</span>
              </p>
            </div>

            <span class="text-xs font-bold px-2 py-1 rounded-lg" style="background:{{ $bg }}; color:{{ $fg }};">
              {{ $label }}
            </span>
          </div>

          @if(in_array($type, ['mcq','tf']))
            <div class="mt-3 text-sm text-slate-700">
              Your answer: <span class="font-semibold">{{ optional($ans->selectedOption)->text ?? '—' }}</span>
            </div>
            <div class="mt-1 text-sm text-slate-700">
              Correct answer: <span class="font-semibold">{{ $correctOpt?->text ?? '—' }}</span>
            </div>
          @else
            <div class="mt-3 text-sm text-slate-700">
              Your answer:
              <div class="mt-2 rounded-xl border border-slate-200 bg-slate-50 p-4 text-slate-800">
                {{ $ans->short_answer ?? '—' }}
              </div>
              <p class="mt-2 text-xs text-slate-500">Short answers are graded by the teacher.</p>
            </div>
          @endif
        </div>
      @endforeach
    </div>

    <div class="mt-6 flex flex-wrap gap-2">
      <a href="{{ route('student.quizzes.history') }}"
         class="px-4 py-2 rounded-xl font-semibold border border-slate-200 bg-white hover:shadow-sm transition">
        Back to Quiz History
      </a>
      <a href="{{ route('student.dashboard') }}"
         class="px-4 py-2 rounded-xl font-semibold text-white shadow-sm hover:shadow-md transition"
         style="background:{{ $cmBlue }};">
        Dashboard
      </a>
    </div>

  </div>
</div>
@endsection
