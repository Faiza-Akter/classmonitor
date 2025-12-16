@extends('layouts.app')

@section('content')
@php $cmBlue='#2463EB'; @endphp

<div class="min-h-[calc(100vh-88px)] bg-white text-slate-900">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-10">

        <p class="text-sm font-semibold text-slate-600">Quizzes</p>
        <h1 class="mt-1 text-3xl font-extrabold">Create Quiz</h1>

        @if($errors->any())
            <div class="mt-5 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-red-800">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('quizzes.store') }}" class="mt-6 rounded-2xl border border-slate-200 bg-white shadow-sm p-6">
            @csrf

            <label class="block text-sm font-semibold text-slate-700">Title</label>
            <input name="title" value="{{ old('title') }}" required
                   class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-slate-200"
                   placeholder="e.g. CSE 220 Quiz 1">

            <label class="block text-sm font-semibold text-slate-700 mt-5">Duration (minutes) <span class="text-slate-400 font-normal">(optional)</span></label>
            <input name="duration" type="number" min="1" max="300" value="{{ old('duration') }}"
                   class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-slate-200"
                   placeholder="e.g. 10">

            <div class="mt-6 flex gap-2">
                <button type="submit"
                        class="px-5 py-2.5 rounded-xl font-semibold text-white shadow-sm hover:shadow-md transition"
                        style="background: {{ $cmBlue }};">
                    Create
                </button>

                <a href="{{ route('quizzes.index') }}"
                   class="px-5 py-2.5 rounded-xl font-semibold border border-slate-200 bg-white hover:shadow-sm transition">
                    Back
                </a>
            </div>
        </form>

    </div>
</div>
@endsection
