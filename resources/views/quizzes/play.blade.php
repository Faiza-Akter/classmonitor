@extends('layouts.app')

@section('content')
@php $cmBlue='#2463EB'; @endphp

<div class="min-h-[calc(100vh-88px)] bg-white text-slate-900">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-10">

        <p class="text-sm font-semibold text-slate-600">Quiz</p>
        <h1 class="mt-1 text-3xl font-extrabold">{{ $quiz->title }}</h1>
        <p class="mt-1 text-sm text-slate-600">Answer all questions and submit.</p>

        @if($errors->any())
            <div class="mt-5 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-red-800">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('quizzes.submit', $quiz) }}" class="mt-6 space-y-4">
            @csrf

            @foreach($quiz->questions as $q)
                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5">
                    <p class="font-extrabold">{{ $q->text }}</p>
                    <p class="text-sm text-slate-600 mt-1">Points: {{ $q->points }}</p>

                    <div class="mt-4 space-y-2">
                        @foreach($q->options as $opt)
                            <label class="flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 cursor-pointer">
                                <input type="radio" name="answers[{{ $q->id }}]" value="{{ $opt->id }}" required>
                                <span>{{ $opt->text }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <button type="submit"
                    class="w-full px-5 py-3 rounded-2xl font-semibold text-white shadow-sm hover:shadow-md transition"
                    style="background: {{ $cmBlue }};">
                Submit Quiz
            </button>
        </form>

    </div>
</div>
@endsection
