<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\QuizAttempt;
use App\Models\QuizAnswer;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    /* ============================
     * TEACHER SIDE
     * ============================ */

    /**
     * Teacher: list quizzes
     * GET /quizzes
     */
    public function teacherIndex(Request $request)
    {
        $teacher = $request->user();

        $quizzes = Quiz::query()
            ->where('teacher_id', $teacher->id)
            ->withCount('questions')
            ->latest('id')
            ->get();

        return view('quizzes.index', compact('quizzes'));
    }

    /**
     * Teacher: create quiz form
     * GET /quizzes/create
     */
    public function teacherCreate()
    {
        return view('quizzes.create');
    }

    /**
     * Teacher: store quiz
     * POST /quizzes
     */
    public function teacherStore(Request $request)
    {
        $teacher = $request->user();

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'duration' => ['nullable', 'integer', 'min:1', 'max:300'], // minutes
        ]);

        $quiz = Quiz::create([
            'teacher_id' => $teacher->id,
            'title' => $data['title'],
            'duration' => $data['duration'] ?? null,
            'status' => 'draft',
        ]);

        return redirect()
            ->route('quizzes.manage', $quiz)
            ->with('success', 'Quiz created. Now add questions.');
    }

    /**
     * Teacher: manage quiz (add questions/options)
     * GET /quizzes/{quiz}/manage
     */
    public function teacherManage(Request $request, Quiz $quiz)
    {
        abort_unless($quiz->teacher_id === $request->user()->id, 403);

        $quiz->load(['questions.options']);

        return view('quizzes.manage', compact('quiz'));
    }

    /**
     * Teacher: add MCQ question + options
     * POST /quizzes/{quiz}/questions
     */
    public function teacherAddQuestion(Request $request, Quiz $quiz)
    {
        abort_unless($quiz->teacher_id === $request->user()->id, 403);

        if ($quiz->status === 'active') {
            return back()->withErrors(['text' => 'You cannot edit questions while the quiz is active. Stop the quiz first.']);
        }

        $data = $request->validate([
            'text' => ['required', 'string', 'max:2000'],
            'points' => ['required', 'integer', 'min:1', 'max:100'],
            'options' => ['required', 'array', 'min:2', 'max:6'],
            'options.*' => ['required', 'string', 'max:255'],
            'correct_index' => ['required', 'integer', 'min:0', 'max:5'],
        ]);

        // Clean options (remove empty, re-index)
        $options = collect($data['options'])
            ->map(fn($v) => trim((string)$v))
            ->filter(fn($v) => $v !== '')
            ->values();

        if ($options->count() < 2) {
            return back()->withErrors(['options' => 'At least 2 valid options are required.'])->withInput();
        }

        $correctIndex = (int)$data['correct_index'];
        if ($correctIndex < 0 || $correctIndex >= $options->count()) {
            $correctIndex = 0;
        }

        $question = Question::create([
            'quiz_id' => $quiz->id,
            'type' => 'mcq',
            'text' => $data['text'],
            'points' => (int)$data['points'],
        ]);

        foreach ($options as $i => $optText) {
            QuestionOption::create([
                'question_id' => $question->id,
                'text' => $optText,
                'is_correct' => ($i === $correctIndex),
            ]);
        }

        return back()->with('success', 'Question added.');
    }

    /**
     * Teacher: edit question form
     * GET /quizzes/{quiz}/questions/{question}/edit
     */
    public function teacherEditQuestion(Request $request, Quiz $quiz, Question $question)
    {
        $teacher = $request->user();

        abort_unless($quiz->teacher_id === $teacher->id, 403);
        abort_unless($question->quiz_id === $quiz->id, 404);

        if ($quiz->status === 'active') {
            return redirect()->route('quizzes.manage', $quiz)
                ->withErrors(['edit' => 'Stop the quiz before editing questions.']);
        }

        $question->load('options');

        return view('quizzes.edit_question', compact('quiz', 'question'));
    }

    /**
     * Teacher: update question
     * PATCH /quizzes/{quiz}/questions/{question}
     */
    public function teacherUpdateQuestion(Request $request, Quiz $quiz, Question $question)
    {
        $teacher = $request->user();

        abort_unless($quiz->teacher_id === $teacher->id, 403);
        abort_unless($question->quiz_id === $quiz->id, 404);

        if ($quiz->status === 'active') {
            return redirect()->route('quizzes.manage', $quiz)
                ->withErrors(['edit' => 'Stop the quiz before editing questions.']);
        }

        $validated = $request->validate([
            'type' => ['required', 'in:mcq,tf,short'],
            'text' => ['required', 'string', 'max:2000'],
            'points' => ['nullable', 'integer', 'min:1', 'max:100'],

            // options for mcq/tf only
            'options' => ['array'],
            'options.*' => ['nullable', 'string', 'max:255'],
            'correct_index' => ['nullable', 'integer', 'min:0', 'max:10'],
        ]);

        $question->update([
            'type' => $validated['type'],
            'text' => $validated['text'],
            'points' => $validated['points'] ?? 1,
        ]);

        // MCQ/TF → replace options
        if (in_array($validated['type'], ['mcq', 'tf'])) {

            $options = collect($validated['options'] ?? [])
                ->map(fn($v) => trim((string)$v))
                ->filter(fn($v) => $v !== '')
                ->values();

            if ($validated['type'] === 'tf') {
                $options = collect(['True', 'False']);
            }

            if ($options->count() < 2) {
                return back()->withErrors(['options' => 'At least 2 options are required.'])->withInput();
            }

            $correctIndex = (int)($validated['correct_index'] ?? 0);
            if ($correctIndex < 0 || $correctIndex >= $options->count()) {
                $correctIndex = 0;
            }

            $question->options()->delete();

            foreach ($options as $i => $optText) {
                $question->options()->create([
                    'text' => $optText,
                    'is_correct' => ($i === $correctIndex),
                ]);
            }
        } else {
            // short answer: remove mcq options
            $question->options()->delete();
        }

        return redirect()->route('quizzes.manage', $quiz)->with('success', 'Question updated successfully.');
    }

    /**
     * Teacher: start quiz
     * POST /quizzes/{quiz}/start
     */
    public function teacherStart(Request $request, Quiz $quiz)
    {
        abort_unless($quiz->teacher_id === $request->user()->id, 403);

        if ($quiz->questions()->count() === 0) {
            return back()->withErrors(['start' => 'Add at least 1 question before starting the quiz.']);
        }

        Quiz::query()
            ->where('teacher_id', $quiz->teacher_id)
            ->where('status', 'active')
            ->where('id', '!=', $quiz->id)
            ->update(['status' => 'closed']);

        $quiz->update(['status' => 'active']);

        return back()->with('success', 'Quiz started.');
    }

    /**
     * Teacher: stop quiz
     * POST /quizzes/{quiz}/stop
     */
    public function teacherStop(Request $request, Quiz $quiz)
    {
        abort_unless($quiz->teacher_id === $request->user()->id, 403);

        $quiz->update(['status' => 'closed']);

        return back()->with('success', 'Quiz ended.');
    }

    /* ============================
     * STUDENT SIDE
     * ============================ */

    /**
     * Student: play quiz (only if active)
     * GET /quizzes/{quiz}/play
     */
    public function studentPlay(Request $request, Quiz $quiz)
    {
        abort_unless($quiz->status === 'active', 403);

        $student = $request->user();

        $attempt = QuizAttempt::firstOrCreate(
            [
                'quiz_id' => $quiz->id,
                'student_id' => $student->id,
            ],
            [
                'started_at' => now(),
                'score' => 0,
            ]
        );

        if ($attempt->submitted_at) {
            return redirect()->route('quizzes.result', $quiz);
        }

        $quiz->load(['questions.options']);

        return view('quizzes.play', compact('quiz', 'attempt'));
    }

    /**
     * Student: submit quiz answers and auto-score MCQ
     * POST /quizzes/{quiz}/submit
     */
    public function studentSubmit(Request $request, Quiz $quiz)
    {
        $student = $request->user();

        $attempt = QuizAttempt::query()
            ->where('quiz_id', $quiz->id)
            ->where('student_id', $student->id)
            ->firstOrFail();

        abort_if($attempt->submitted_at, 403);

        $quiz->load(['questions.options']);

        $rules = [];
        foreach ($quiz->questions as $q) {
            $rules["answers.{$q->id}"] = ['required', 'integer'];
        }
        $data = $request->validate($rules);

        $score = 0;

        foreach ($quiz->questions as $question) {
            $selectedOptionId = (int)($data['answers'][$question->id] ?? 0);

            $option = $question->options->firstWhere('id', $selectedOptionId);
            $isCorrect = $option ? (bool)$option->is_correct : false;

            QuizAnswer::updateOrCreate(
                [
                    'attempt_id' => $attempt->id,
                    'question_id' => $question->id,
                ],
                [
                    'selected_option_id' => $selectedOptionId,
                    'short_answer' => null,
                    'is_correct' => $isCorrect,
                ]
            );

            if ($isCorrect) {
                $score += (int)$question->points;
            }
        }

        $attempt->update([
            'submitted_at' => now(),
            'score' => $score,
        ]);

        return redirect()->route('quizzes.result', $quiz);
    }

    /**
     * Student: view result
     * GET /quizzes/{quiz}/result
     */
    public function studentResult(Request $request, Quiz $quiz)
    {
        $student = $request->user();

        $attempt = QuizAttempt::query()
            ->where('quiz_id', $quiz->id)
            ->where('student_id', $student->id)
            ->with([
                'answers.question.options',
                'answers.selectedOption',
            ])
            ->firstOrFail();

        return view('quizzes.result', compact('quiz', 'attempt'));
    }
}
