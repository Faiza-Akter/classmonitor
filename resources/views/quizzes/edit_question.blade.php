@extends('layouts.app')

@section('content')
@php
  $cmBlue   = '#2463EB';
  $cmGreen  = '#8BDE63';
  $cmYellow = '#EDB70A';
@endphp

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
  <div class="mb-6">
    <p class="text-sm text-slate-600">Quiz</p>
    <h1 class="text-2xl font-extrabold text-slate-900">Edit Question</h1>
    <p class="text-sm text-slate-600 mt-1">{{ $quiz->title }}</p>
  </div>

  @if ($errors->any())
    <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
      <ul class="list-disc pl-5">
        @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('quizzes.questions.update', [$quiz, $question]) }}"
        class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-5">
    @csrf
    @method('PATCH')

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
      <div>
        <label class="text-sm font-semibold text-slate-700">Type</label>
        <select name="type" class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2">
          <option value="mcq"  @selected(old('type', $question->type) === 'mcq')>MCQ</option>
          <option value="tf"   @selected(old('type', $question->type) === 'tf')>True/False</option>
          <option value="short" @selected(old('type', $question->type) === 'short')>Short Answer</option>
        </select>
      </div>

      <div>
        <label class="text-sm font-semibold text-slate-700">Points</label>
        <input type="number" name="points" min="1" max="100"
               value="{{ old('points', $question->points ?? 1) }}"
               class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2" />
      </div>

      <div class="sm:col-span-1 flex items-end">
        <a href="{{ route('quizzes.manage', $quiz) }}"
           class="w-full text-center rounded-xl border border-slate-200 px-4 py-2 font-semibold text-slate-900 hover:bg-slate-50">
          Back
        </a>
      </div>
    </div>

    <div>
      <label class="text-sm font-semibold text-slate-700">Question</label>
      <textarea name="text" rows="4"
                class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2"
                placeholder="Write the question...">{{ old('text', $question->text) }}</textarea>
    </div>

    {{-- Options (for MCQ/TF) --}}
    @php
      $oldType = old('type', $question->type);
      $existingOptions = $question->options ?? collect();
      $optionsForForm = old('options') ?? $existingOptions->pluck('text')->toArray();
      $correctIndex = old('correct_index');

      if ($correctIndex === null) {
        $correctIndex = $existingOptions->values()->search(fn($o) => (bool)$o->is_correct);
        $correctIndex = ($correctIndex === false) ? 0 : $correctIndex;
      }
    @endphp

    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
      <div class="flex items-center justify-between">
        <div>
          <p class="font-bold text-slate-900">Options</p>
          <p class="text-xs text-slate-600">For MCQ/TF only. Choose the correct option.</p>
        </div>
        <span class="text-xs font-semibold px-3 py-1 rounded-full"
              style="background: rgba(36,99,235,.10); color: {{ $cmBlue }};">
          Edit
        </span>
      </div>

      <div class="mt-4 space-y-3">
        @for ($i = 0; $i < 4; $i++)
          <div class="flex gap-3 items-center">
            <input type="radio" name="correct_index" value="{{ $i }}"
                   @checked((int)$correctIndex === $i)
                   class="h-4 w-4" />
            <input type="text" name="options[]"
                   value="{{ $optionsForForm[$i] ?? '' }}"
                   class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2"
                   placeholder="Option {{ $i+1 }}" />
          </div>
        @endfor
      </div>

      <p class="text-xs text-slate-500 mt-3">
        If you select <b>True/False</b>, options will automatically become <b>True</b> and <b>False</b> after saving.
      </p>
    </div>

    <div class="flex flex-wrap gap-3">
      <button type="submit"
              class="rounded-xl px-5 py-2.5 font-bold text-white shadow-sm hover:shadow-md"
              style="background: {{ $cmBlue }};">
        Save Changes
      </button>

      <a href="{{ route('quizzes.manage', $quiz) }}"
         class="rounded-xl border border-slate-200 px-5 py-2.5 font-semibold text-slate-900 hover:bg-slate-50">
        Cancel
      </a>
    </div>
  </form>
</div>
@endsection
