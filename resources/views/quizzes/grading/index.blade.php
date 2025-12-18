@extends('layouts.app')

@section('content')
<div class="min-h-[calc(100vh-88px)] bg-white text-slate-900">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">

    <div class="flex items-center justify-between gap-4">
      <div>
        <p class="text-sm font-semibold text-slate-600">Manual Grading</p>
        <h1 class="mt-1 text-3xl font-extrabold">{{ $quiz->title }}</h1>
        <p class="mt-1 text-sm text-slate-600">Open an attempt and grade short answers.</p>
      </div>

      <a href="{{ route('quizzes.manage', $quiz) }}"
         class="px-4 py-2 rounded-xl font-semibold border border-slate-200 bg-white hover:shadow-sm transition">
        Back to Manage
      </a>
    </div>

    <div class="mt-6 rounded-2xl border border-slate-200 bg-white shadow-sm overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead>
          <tr class="text-left text-slate-600">
            <th class="px-5 py-3">Student</th>
            <th class="px-5 py-3">Email</th>
            <th class="px-5 py-3">Submitted</th>
            <th class="px-5 py-3">Score</th>
            <th class="px-5 py-3"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @forelse($attempts as $a)
            <tr>
              <td class="px-5 py-4 font-semibold">{{ $a->student?->name ?? 'Student' }}</td>
              <td class="px-5 py-4 text-slate-600">{{ $a->student?->email ?? '' }}</td>
              <td class="px-5 py-4 text-slate-600">
                {{ optional($a->submitted_at)->timezone(config('app.timezone'))->format('Y-m-d h:i A') ?? 'Not submitted' }}
              </td>
              <td class="px-5 py-4 font-extrabold">{{ $a->score ?? 0 }}</td>
              <td class="px-5 py-4">
                <a href="{{ route('quizzes.grading.show', [$quiz, $a]) }}"
                   class="px-4 py-2 rounded-xl font-semibold border border-slate-200 bg-white hover:shadow-sm transition">
                  Grade
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="px-5 py-8 text-slate-600">No attempts yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-4">
      {{ $attempts->links() }}
    </div>

  </div>
</div>
@endsection
