@extends('layouts.app')

@section('content')
@php
  $cmGreen='#8BDE63';
@endphp

<div class="min-h-[calc(100vh-88px)] bg-white text-slate-900">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">

    <div class="flex items-start justify-between gap-4">
      <div>
        <p class="text-sm font-semibold text-slate-600">Grade Attempt</p>
        <h1 class="mt-1 text-3xl font-extrabold">{{ $quiz->title }}</h1>
        <p class="mt-1 text-sm text-slate-600">
          Student: <span class="font-semibold">{{ $attempt->student?->name ?? 'Student' }}</span>
          • Score: <span class="font-extrabold">{{ $attempt->score ?? 0 }}</span>
        </p>
      </div>

      <a href="{{ route('quizzes.grading.index', $quiz) }}"
         class="px-4 py-2 rounded-xl font-semibold border border-slate-200 bg-white hover:shadow-sm transition">
        Back
      </a>
    </div>

    @if(session('success'))
      <div class="mt-5 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-green-800">
        {{ session('success') }}
      </div>
    @endif

    @if($errors->any())
      <div class="mt-5 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-red-800">
        <ul class="list-disc pl-5">
          @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form class="mt-6 space-y-4" method="POST" action="{{ route('quizzes.grading.update', [$quiz, $attempt]) }}">
      @csrf
      @method('PATCH')

      @foreach($attempt->answers as $ans)
        @php $q = $ans->question; @endphp
        @if(($q?->type ?? '') === 'short')
          <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-6">
            <div class="flex items-start justify-between gap-4">
              <div>
                <p class="font-extrabold">{{ $q->text }}</p>
                <p class="text-sm text-slate-600 mt-1">Points: {{ $q->points }}</p>
              </div>

              <div class="flex items-center gap-2">
                <label class="text-sm font-semibold text-slate-700">Correct?</label>
                <select name="grades[{{ $ans->id }}]" class="rounded-xl border border-slate-200 px-4 py-2">
                  <option value="0" {{ $ans->is_correct === false ? 'selected' : '' }}>Wrong</option>
                  <option value="1" {{ $ans->is_correct === true ? 'selected' : '' }}>Correct</option>
                </select>
              </div>
            </div>

            <div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 p-4">
              <p class="text-xs font-bold text-slate-500">Student Answer</p>
              <p class="mt-1 text-sm text-slate-800 whitespace-pre-wrap">{{ $ans->short_answer ?? '—' }}</p>
            </div>
          </div>
        @endif
      @endforeach

      <button type="submit"
              class="px-5 py-2.5 rounded-xl font-semibold text-white shadow-sm hover:shadow-md transition"
              style="background: {{ $cmGreen }}; color:#0B1B0F;">
        Save Grading + Update Score
      </button>
    </form>

  </div>
</div>
@endsection
