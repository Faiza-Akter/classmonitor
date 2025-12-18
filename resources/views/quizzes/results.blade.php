@extends('layouts.app')

@section('content')
@php
  $cmBlue='#2463EB'; $cmGreen='#8BDE63'; $cmYellow='#EDB70A';
@endphp

<div class="min-h-[calc(100vh-88px)] bg-white text-slate-900">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
      <div>
        <p class="text-sm font-semibold text-slate-600">Teacher • Quiz Results</p>
        <h1 class="mt-1 text-3xl font-extrabold">{{ $quiz->title }}</h1>
        <p class="mt-1 text-sm text-slate-600">
          Max score: <span class="font-semibold">{{ $maxScore }}</span>
          <span class="mx-2">•</span>
          Status:
          <span class="font-semibold">{{ strtoupper($quiz->status) }}</span>
        </p>
      </div>

      <div class="flex flex-wrap gap-2">
        <a href="{{ route('quizzes.manage', $quiz) }}"
           class="px-4 py-2 rounded-xl font-semibold border border-slate-200 bg-white shadow-sm hover:shadow-md transition">
          Manage Quiz
        </a>

        <a href="{{ route('quizzes.leaderboard', $quiz) }}"
           class="px-4 py-2 rounded-xl font-semibold text-white shadow-sm hover:shadow-md transition"
           style="background:{{ $cmBlue }};">
          Leaderboard
        </a>
      </div>
    </div>

    {{-- Snapshot cards --}}
    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5">
        <p class="text-sm font-semibold text-slate-600">Total Submissions</p>
        <p class="mt-1 text-3xl font-extrabold text-slate-900">{{ $totalSubmissions }}</p>
      </div>

      <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5">
        <p class="text-sm font-semibold text-slate-600">Average Score</p>
        <p class="mt-1 text-3xl font-extrabold" style="color:{{ $cmBlue }};">
          {{ $avgScore === null ? '—' : $avgScore }}
          <span class="text-base font-bold text-slate-500">/ {{ $maxScore }}</span>
        </p>
      </div>

      <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5">
        <p class="text-sm font-semibold text-slate-600">Top Score</p>
        <p class="mt-1 text-3xl font-extrabold" style="color:{{ $cmGreen }};">
          {{ $topScore === null ? '—' : $topScore }}
          <span class="text-base font-bold text-slate-500">/ {{ $maxScore }}</span>
        </p>
      </div>

      <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5">
        <p class="text-sm font-semibold text-slate-600">Quick Actions</p>
        <div class="mt-3 flex flex-wrap gap-2">
          <a href="{{ route('quizzes.grading.index', $quiz) }}"
             class="px-4 py-2 rounded-xl font-semibold border border-slate-200 bg-white shadow-sm hover:shadow-md transition">
            Manual Grading
          </a>
        </div>
      </div>
    </div>

    {{-- Attempts table --}}
    <div class="mt-6 rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
      <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
        <div>
          <p class="text-sm font-extrabold text-slate-900">Submissions</p>
          <p class="text-xs text-slate-500">Sorted by score (desc)</p>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full">
          <thead class="bg-slate-50">
            <tr class="text-left text-xs font-bold uppercase tracking-wider text-slate-500">
              <th class="px-5 py-4">Student</th>
              <th class="px-5 py-4">Email</th>
              <th class="px-5 py-4">Score</th>
              <th class="px-5 py-4">Submitted</th>
            </tr>
          </thead>

          <tbody class="divide-y divide-slate-200">
            @forelse($attempts as $a)
              <tr class="hover:bg-slate-50/60 transition">
                <td class="px-5 py-4 font-semibold text-slate-900">{{ $a->student?->name ?? 'Student' }}</td>
                <td class="px-5 py-4 text-sm text-slate-700">{{ $a->student?->email ?? '—' }}</td>
                <td class="px-5 py-4 font-extrabold" style="color:{{ $cmBlue }};">
                  {{ (int)$a->score }}
                  <span class="text-slate-500 font-bold">/ {{ $maxScore }}</span>
                </td>
                <td class="px-5 py-4 text-sm text-slate-700">
                  {{ optional($a->submitted_at)?->timezone('Asia/Dhaka')->format('Y-m-d h:i A') ?? '—' }}
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="px-5 py-10 text-center text-sm text-slate-600">
                  No one has submitted this quiz yet.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="px-5 py-4 border-t border-slate-200">
        {{ $attempts->links() }}
      </div>
    </div>

  </div>
</div>
@endsection
