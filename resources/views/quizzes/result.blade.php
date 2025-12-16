@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8">

    <h1 class="text-2xl font-extrabold mb-4">{{ $quiz->title }} – Result</h1>

    <p class="mb-6 text-lg">
        Score: <span class="font-bold">{{ $attempt->score }}</span>
    </p>

    @foreach($attempt->answers as $ans)
        <div class="mb-4 p-4 border rounded-xl bg-white">
            <p class="font-semibold">{{ $ans->question->text }}</p>
            <p class="{{ $ans->is_correct ? 'text-green-600' : 'text-red-600' }}">
                {{ $ans->is_correct ? 'Correct' : 'Wrong' }}
            </p>
        </div>
    @endforeach

</div>
@endsection
