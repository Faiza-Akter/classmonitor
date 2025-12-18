@extends('layouts.app')

@section('content')
@php
    $cmGreen='#8BDE63';
    $cmBlue='#2463EB';
    $cmYellow='#EDB70A';
@endphp

<div class="min-h-[calc(100vh-88px)] bg-white text-slate-900">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-10">

        <p class="text-sm font-semibold text-slate-600">Result</p>
        <h1 class="mt-1 text-3xl font-extrabold">{{ $quiz->title }}</h1>

        <div class="mt-6 rounded-2xl border border-slate-200 bg-white shadow-sm p-6">
            <p class="text-sm text-slate-600">Your Score</p>
            <p class="mt-1 text-4xl font-extrabold" style="color: {{ $cmBlue }};">
                {{ $attempt->score }}
                @isset($maxScore)
                    <span class="text-base font-bold text-slate-500">/ {{ $maxScore }}</span>
                @endisset
            </p>
            <p class="mt-2 text-sm text-slate-600">
                Submitted at:
                <span class="font-semibold">
                    {{ optional($attempt->submitted_at)?->timezone('Asia/Dhaka')->format('Y-m-d h:i A') ?? '—' }}
                </span>
            </p>
        </div>

        <div class="mt-6 space-y-4">
            @foreach($attempt->answers as $ans)
                @php
                    $q = $ans->question;
                    $type = $q->type ?? 'mcq';

                    // Badge (handles short answer pending)
                    $label = 'Wrong';
                    $bg = 'rgba(239,68,68,.15)'; $fg = '#7f1d1d';

                    if ($type === 'short' && $ans->is_correct === null) {
                        $label = 'Pending Review';
                        $bg = 'rgba(237,183,10,.18)'; $fg = '#3a2b00';
                    } elseif ($ans->is_correct === true) {
                        $label = 'Correct';
                        $bg = 'rgba(139,222,99,.22)'; $fg = '#0B1B0F';
                    }
                @endphp

                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="font-extrabold">{{ $q->text }}</p>
                            <p class="text-sm text-slate-600 mt-1">Points: {{ $q->points }}</p>
                        </div>

                        <span class="text-xs font-bold px-2 py-1 rounded-lg"
                              style="background: {{ $bg }}; color: {{ $fg }};">
                            {{ $label }}
                        </span>
                    </div>

                    {{-- MCQ / TF --}}
                    @if(in_array($type, ['mcq','tf']))
                        <div class="mt-3 text-sm text-slate-700">
                            Selected:
                            <span class="font-semibold">
                                {{ optional($ans->selectedOption)->text ?? '—' }}
                            </span>
                        </div>

                    {{-- SHORT ANSWER --}}
                    @else
                        <div class="mt-3 text-sm text-slate-700">
                            Your answer:
                            <div class="mt-2 rounded-xl border border-slate-200 bg-slate-50 p-4 text-slate-800">
                                {{ $ans->short_answer ?? '—' }}
                            </div>
                            <p class="mt-2 text-xs text-slate-500">This question will be graded by the teacher.</p>
                        </div>
                    @endif
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
