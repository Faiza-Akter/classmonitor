@extends('layouts.app')

@section('content')
@php $cmGreen='#8BDE63'; $cmBlue='#2463EB'; @endphp

<div class="min-h-[calc(100vh-88px)] bg-white text-slate-900">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-10">

        <p class="text-sm font-semibold text-slate-600">Result</p>
        <h1 class="mt-1 text-3xl font-extrabold">{{ $quiz->title }}</h1>

        <div class="mt-6 rounded-2xl border border-slate-200 bg-white shadow-sm p-6">
            <p class="text-sm text-slate-600">Your Score</p>
            <p class="mt-1 text-4xl font-extrabold" style="color: {{ $cmBlue }};">{{ $attempt->score }}</p>
            <p class="mt-2 text-sm text-slate-600">
                Submitted at: <span class="font-semibold">{{ optional($attempt->submitted_at)->format('Y-m-d h:i A') }}</span>
            </p>
        </div>

        <div class="mt-6 space-y-4">
            @foreach($attempt->answers as $ans)
                @php
                    $q = $ans->question;
                    $selectedId = $ans->selected_option_id;
                @endphp

                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="font-extrabold">{{ $q->text }}</p>
                            <p class="text-sm text-slate-600 mt-1">Points: {{ $q->points }}</p>
                        </div>

                        <span class="text-xs font-bold px-2 py-1 rounded-lg"
                              style="background: {{ $ans->is_correct ? 'rgba(139,222,99,.22)' : 'rgba(239,68,68,.15)' }};
                                     color: {{ $ans->is_correct ? '#0B1B0F' : '#7f1d1d' }};">
                            {{ $ans->is_correct ? 'Correct' : 'Wrong' }}
                        </span>
                    </div>

                    <div class="mt-3 text-sm text-slate-700">
                        Selected:
                        <span class="font-semibold">
                            {{ optional($ans->selectedOption)->text ?? '—' }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center px-4 py-2 rounded-xl font-semibold border border-slate-200 bg-white hover:shadow-sm transition">
                Back to Dashboard
            </a>
        </div>

    </div>
</div>
@endsection
