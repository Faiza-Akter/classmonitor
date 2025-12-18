@extends('layouts.app')

@section('content')
@php
  $cmBlue='#2463EB'; $cmGreen='#8BDE63'; $cmYellow='#EDB70A';
@endphp

<div class="min-h-[calc(100vh-88px)] bg-white text-slate-900">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">

        {{-- Header --}}
        <div class="flex items-start justify-between gap-4">
            <div class="min-w-0">
                <p class="text-sm font-semibold text-slate-600">Edit Question</p>
                <h1 class="mt-1 text-2xl sm:text-3xl font-extrabold truncate">{{ $quiz->title }}</h1>
                <p class="mt-1 text-sm text-slate-600">Update question text, points, options and correct answer.</p>
            </div>

            <div class="flex gap-2 shrink-0">
                <a href="{{ route('quizzes.manage', $quiz) }}"
                   class="px-4 py-2 rounded-xl font-semibold border border-slate-200 bg-white hover:shadow-sm transition">
                    Back to Manage
                </a>
            </div>
        </div>

        {{-- Alerts --}}
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

        {{-- Edit form --}}
        <div class="mt-6 rounded-2xl border border-slate-200 bg-white shadow-sm p-6">
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <h2 class="text-lg font-extrabold">Question Details</h2>
                    <p class="text-sm text-slate-600 mt-1">Type: <span class="font-semibold">{{ strtoupper($question->type ?? 'MCQ') }}</span></p>
                </div>

                <span class="text-xs font-bold px-2 py-1 rounded-lg"
                      style="background: rgba(237,183,10,.18); color:#3a2b00;">
                    {{ strtoupper($question->type ?? 'MCQ') }}
                </span>
            </div>

            <form method="POST" action="{{ route('quizzes.questions.update', [$quiz, $question]) }}" class="mt-5 space-y-5">
                @csrf
                @method('PATCH')

                {{-- Type (optional UI) --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700">Question Type</label>
                    <select name="type" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3">
                        <option value="mcq" @selected(old('type', $question->type) === 'mcq')>MCQ</option>
                        <option value="tf" @selected(old('type', $question->type) === 'tf')>True / False</option>
                        <option value="short" @selected(old('type', $question->type) === 'short')>Short Answer</option>
                    </select>
                    <p class="text-xs text-slate-500 mt-2">
                        (For now MCQ is recommended. TF/Short will work based on your controller, but student play UI may still be MCQ-only.)
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700">Question</label>
                    <textarea name="text" rows="3" required
                              class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-slate-200"
                              placeholder="Write the question...">{{ old('text', $question->text) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700">Points</label>
                    <input type="number" name="points" min="1" max="100"
                           value="{{ old('points', $question->points ?? 1) }}" required
                           class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-slate-200">
                </div>

                {{-- Options (MCQ/TF) --}}
                @php
                    $existingOptions = $question->options ?? collect();
                    $correctIndex = 0;
                    foreach($existingOptions as $i => $opt){
                        if($opt->is_correct) { $correctIndex = $i; break; }
                    }
                @endphp

                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-sm font-extrabold text-slate-900">Options</p>
                    <p class="text-xs text-slate-600 mt-1">For MCQ: 2–6 options. For TF: it will be auto-set to True/False.</p>

                    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @for($i=0; $i<6; $i++)
                            <div>
                                <label class="block text-sm font-semibold text-slate-700">Option {{ $i+1 }}</label>
                                <input name="options[]"
                                       value="{{ old("options.$i", $existingOptions[$i]->text ?? '') }}"
                                       class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-slate-200"
                                       placeholder="Option text (leave blank to remove)">
                            </div>
                        @endfor
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-semibold text-slate-700">Correct Option</label>
                        <select name="correct_index" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3">
                            @for($i=0; $i<6; $i++)
                                <option value="{{ $i }}"
                                    @selected((string)old('correct_index', $correctIndex) === (string)$i)>
                                    Option {{ $i+1 }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="flex flex-wrap gap-2">
                    <button type="submit"
                            class="px-5 py-2.5 rounded-xl font-semibold text-white shadow-sm hover:shadow-md transition"
                            style="background: {{ $cmBlue }};">
                        Save Changes
                    </button>

                    <a href="{{ route('quizzes.manage', $quiz) }}"
                       class="px-5 py-2.5 rounded-xl font-semibold border border-slate-200 bg-white hover:shadow-sm transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
