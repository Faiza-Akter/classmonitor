@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8">

    <h1 class="text-2xl font-extrabold mb-6">{{ $quiz->title }}</h1>

    <form method="POST" action="{{ route('quizzes.submit', $quiz) }}">
        @csrf

        @foreach($quiz->questions as $q)
            <div class="mb-6 p-5 border rounded-xl bg-white">
                <p class="font-semibold mb-3">
                    {{ $loop->iteration }}. {{ $q->text }}
                </p>

                @foreach($q->options as $opt)
                    <label class="block mb-2">
                        <input type="radio"
                               name="answers[{{ $q->id }}]"
                               value="{{ $opt->id }}"
                               required>
                        {{ $opt->text }}
                    </label>
                @endforeach
            </div>
        @endforeach

        <button class="px-6 py-3 rounded-xl bg-blue-600 text-white font-semibold">
            Submit Quiz
        </button>
    </form>

</div>
@endsection
