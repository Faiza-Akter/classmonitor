@extends('layouts.app')

@section('content')
@php
  $cmBlue='#2463EB'; $cmGreen='#8BDE63'; $cmYellow='#EDB70A';
@endphp

<div class="min-h-[calc(100vh-88px)] bg-white text-slate-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">

        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-sm font-semibold text-slate-600">Quiz</p>
                <h1 class="mt-1 text-3xl font-extrabold">{{ $quiz->title }}</h1>
                <p class="mt-1 text-sm text-slate-600">Add MCQ questions + options. Short-answer supported (manual grading).</p>
            </div>

            <div class="flex flex-wrap gap-2 justify-end">
                <a href="{{ route('quizzes.index') }}"
                   class="px-4 py-2 rounded-xl font-semibold border border-slate-200 bg-white hover:shadow-sm transition">
                    Back
                </a>

                <a href="{{ route('quizzes.leaderboard', $quiz) }}"
                   class="px-4 py-2 rounded-xl font-semibold text-white shadow-sm hover:shadow-md transition"
                   style="background: {{ $cmBlue }};">
                    Leaderboard
                </a>

                <a href="{{ route('quizzes.grading.index', $quiz) }}"
                   class="px-4 py-2 rounded-xl font-semibold border border-slate-200 bg-white hover:shadow-sm transition">
                    Manual Grading
                </a>

                @if($quiz->status !== 'active')
                    <form method="POST" action="{{ route('quizzes.start', $quiz) }}">
                        @csrf
                        <button class="px-4 py-2 rounded-xl font-semibold shadow-sm hover:shadow-md transition"
                                style="background: {{ $cmGreen }}; color:#0B1B0F;">
                            Start Quiz
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('quizzes.stop', $quiz) }}">
                        @csrf
                        <button class="px-4 py-2 rounded-xl font-semibold border border-slate-200 bg-white hover:shadow-sm transition">
                            Stop Quiz
                        </button>
                    </form>
                @endif
            </div>
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

        <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-4">

            {{-- Add question --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-6">
                <h2 class="text-lg font-extrabold">Add Question</h2>
                <p class="text-sm text-slate-600 mt-1">MCQ (auto-score) or Short (manual grade).</p>

                <form method="POST" action="{{ route('quizzes.questions.store', $quiz) }}" class="mt-5 space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Type</label>
                        <select name="type" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3">
                            <option value="mcq" selected>MCQ</option>
                            <option value="short">Short Answer</option>
                        </select>
                        <p class="text-xs text-slate-500 mt-2">Short answers require manual grading.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Question</label>
                        <textarea name="text" rows="3" required
                                  class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-slate-200"
                                  placeholder="Write the question...">{{ old('text') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Points</label>
                        <input type="number" name="points" min="1" max="100" value="{{ old('points', 1) }}" required
                               class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-slate-200">
                    </div>

                    {{-- MCQ options (optional: still submitted, controller will ignore for short) --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @for($i=0; $i<4; $i++)
                            <div>
                                <label class="block text-sm font-semibold text-slate-700">Option {{ $i+1 }} (MCQ)</label>
                                <input name="options[]" value="{{ old("options.$i") }}"
                                       class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-slate-200"
                                       placeholder="Option text">
                            </div>
                        @endfor
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Correct Option (MCQ)</label>
                        <select name="correct_index" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3">
                            <option value="0">Option 1</option>
                            <option value="1">Option 2</option>
                            <option value="2">Option 3</option>
                            <option value="3">Option 4</option>
                        </select>
                    </div>

                    <button type="submit"
                            class="px-5 py-2.5 rounded-xl font-semibold text-white shadow-sm hover:shadow-md transition"
                            style="background: {{ $cmBlue }};">
                        Add Question
                    </button>
                </form>
            </div>

            {{-- Existing questions --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-6">
                <h2 class="text-lg font-extrabold">Questions</h2>
                <p class="text-sm text-slate-600 mt-1">Total: {{ $quiz->questions->count() }}</p>

                <div class="mt-5 space-y-4">
                    @forelse($quiz->questions as $q)
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="pr-3">
                                    <p class="font-extrabold">{{ $q->text }}</p>
                                    <p class="text-sm text-slate-600 mt-1">Points: {{ $q->points }}</p>
                                </div>

                                <div class="flex items-center gap-2 shrink-0">
                                    <span class="text-xs font-bold px-2 py-1 rounded-lg"
                                          style="background: rgba(237,183,10,.18); color:#3a2b00;">
                                        {{ strtoupper($q->type ?? 'MCQ') }}
                                    </span>

                                    <a href="{{ route('quizzes.questions.edit', [$quiz, $q]) }}"
                                       class="px-4 py-2 rounded-xl font-semibold border border-slate-200 bg-white hover:shadow-sm transition">
                                        Edit
                                    </a>

                                    {{-- ✅ Delete beside edit --}}
                                    <form method="POST" action="{{ route('quizzes.questions.destroy', [$quiz, $q]) }}"
                                          onsubmit="return confirm('Delete this question?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-4 py-2 rounded-xl font-semibold border border-slate-200 bg-white hover:shadow-sm transition">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>

                            @if(($q->type ?? 'mcq') === 'mcq')
                                <ul class="mt-3 space-y-1">
                                    @foreach($q->options as $opt)
                                        <li class="text-sm flex items-center gap-2">
                                            <span class="inline-block w-2 h-2 rounded-full"
                                                  style="background: {{ $opt->is_correct ? $cmGreen : '#cbd5e1' }};"></span>
                                            <span class="{{ $opt->is_correct ? 'font-semibold' : '' }}">{{ $opt->text }}</span>
                                            @if($opt->is_correct)
                                                <span class="text-xs font-bold" style="color: {{ $cmGreen }};">(correct)</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="mt-3 text-sm text-slate-600">
                                    Short answer question (manual grading).
                                </p>
                            @endif
                        </div>
                    @empty
                        <div class="text-slate-600">No questions yet.</div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
