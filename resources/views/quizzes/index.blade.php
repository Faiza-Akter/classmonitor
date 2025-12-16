@extends('layouts.app')

@section('content')
@php
    $cmBlue   = '#2463EB';
    $cmGreen  = '#8BDE63';
    $cmYellow = '#EDB70A';
@endphp

<div class="min-h-[calc(100vh-88px)] bg-white text-slate-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">

        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-sm font-semibold text-slate-600">Quizzes</p>
                <h1 class="mt-1 text-3xl font-extrabold">Your Quizzes</h1>
                <p class="mt-1 text-sm text-slate-600">Create quizzes, add questions, start/stop.</p>
            </div>

            <a href="{{ route('quizzes.create') }}"
               class="inline-flex items-center px-4 py-2 rounded-xl font-semibold text-white shadow-sm hover:shadow-md transition"
               style="background: {{ $cmBlue }};">
                + Create Quiz
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

        <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-4">
            @forelse($quizzes as $quiz)
                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h2 class="text-lg font-extrabold text-slate-900">{{ $quiz->title }}</h2>
                            <p class="text-sm text-slate-600 mt-1">
                                Questions: <span class="font-semibold">{{ $quiz->questions_count }}</span>
                                @if($quiz->duration)
                                    • Duration: <span class="font-semibold">{{ $quiz->duration }} min</span>
                                @endif
                            </p>

                            <div class="mt-2 text-xs font-semibold">
                                Status:
                                @php
                                    $badge = match($quiz->status){
                                        'active' => ['bg' => 'rgba(139,222,99,.22)', 'text' => '#0B1B0F', 'label' => 'ACTIVE'],
                                        'closed' => ['bg' => 'rgba(237,183,10,.22)', 'text' => '#3a2b00', 'label' => 'CLOSED'],
                                        default => ['bg' => 'rgba(36,99,235,.14)', 'text' => '#0b1b5a', 'label' => 'DRAFT'],
                                    };
                                @endphp
                                <span class="px-2 py-1 rounded-lg" style="background:{{ $badge['bg'] }}; color:{{ $badge['text'] }};">
                                    {{ $badge['label'] }}
                                </span>
                            </div>
                        </div>

                        <a href="{{ route('quizzes.manage', $quiz) }}"
                           class="px-3 py-2 rounded-xl font-semibold border border-slate-200 bg-white hover:shadow-sm transition">
                            Manage
                        </a>
                    </div>

                    <div class="mt-4 flex flex-wrap gap-2">
                        @if($quiz->status !== 'active')
                            <form method="POST" action="{{ route('quizzes.start', $quiz) }}">
                                @csrf
                                <button class="px-4 py-2 rounded-xl font-semibold text-white shadow-sm hover:shadow-md transition"
                                        style="background: {{ $cmGreen }}; color:#0B1B0F;">
                                    Start
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('quizzes.stop', $quiz) }}">
                                @csrf
                                <button class="px-4 py-2 rounded-xl font-semibold border border-slate-200 bg-white hover:shadow-sm transition">
                                    Stop
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('quizzes.play', $quiz) }}"
                           class="px-4 py-2 rounded-xl font-semibold text-white shadow-sm hover:shadow-md transition"
                           style="background: {{ $cmBlue }};">
                            Student Play Link
                        </a>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-slate-200 bg-white p-6 text-slate-600">
                    No quizzes yet. Click <span class="font-semibold">Create Quiz</span>.
                </div>
            @endforelse
        </div>

    </div>
</div>
@endsection
