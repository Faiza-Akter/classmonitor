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

    public function teacherCreate()
    {
        return view('quizzes.create');
    }

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

    public function teacherManage(Request $request, Quiz $quiz)
    {
        abort_unless($quiz->teacher_id === $request->user()->id, 403);

        $quiz->load(['questions.options']);

        return view('quizzes.manage', compact('quiz'));
    }

    /**
     * Teacher: Add question
     * Supports: mcq, tf, short
     */
    public function teacherAddQuestion(Request $request, Quiz $quiz)
    {
        abort_unless($quiz->teacher_id === $request->user()->id, 403);

        if ($quiz->status === 'active') {
            return back()->withErrors(['text' => 'You cannot edit questions while the quiz is active. Stop the quiz first.']);
        }

        $data = $request->validate([
            'type' => ['required', 'in:mcq,tf,short'],
            'text' => ['required', 'string', 'max:2000'],
            'points' => ['required', 'integer', 'min:1', 'max:100'],

            // Options only needed for mcq/tf
            'options' => ['nullable', 'array', 'max:10'],
            'options.*' => ['nullable', 'string', 'max:255'],
            'correct_index' => ['nullable', 'integer', 'min:0', 'max:10'],
        ]);

        $type = $data['type'];

        $question = Question::create([
            'quiz_id' => $quiz->id,
            'type' => $type,
            'text' => $data['text'],
            'points' => (int) $data['points'],
        ]);

        // SHORT: no options
        if ($type === 'short') {
            return back()->with('success', 'Short-answer question added. (Manual grading needed)');
        }

        // TF: force options True/False
        if ($type === 'tf') {
            $options = collect(['True', 'False']);
            $correctIndex = (int) ($data['correct_index'] ?? 0);
            if ($correctIndex < 0 || $correctIndex > 1)
                $correctIndex = 0;

            foreach ($options as $i => $optText) {
                QuestionOption::create([
                    'question_id' => $question->id,
                    'text' => $optText,
                    'is_correct' => ($i === $correctIndex),
                ]);
            }

            return back()->with('success', 'True/False question added.');
        }

        // MCQ: validate options (2–6 recommended)
        $options = collect($data['options'] ?? [])
            ->map(fn($v) => trim((string) $v))
            ->filter(fn($v) => $v !== '')
            ->values();

        if ($options->count() < 2) {
            $question->delete();
            return back()->withErrors(['options' => 'MCQ needs at least 2 options.'])->withInput();
        }

        if ($options->count() > 6) {
            $options = $options->take(6)->values();
        }

        $correctIndex = (int) ($data['correct_index'] ?? 0);
        if ($correctIndex < 0 || $correctIndex >= $options->count()) {
            $correctIndex = 0;
        }

        foreach ($options as $i => $optText) {
            QuestionOption::create([
                'question_id' => $question->id,
                'text' => $optText,
                'is_correct' => ($i === $correctIndex),
            ]);
        }

        return back()->with('success', 'MCQ question added.');
    }

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

    public function teacherStop(Request $request, Quiz $quiz)
    {
        abort_unless($quiz->teacher_id === $request->user()->id, 403);

        $quiz->update(['status' => 'closed']);

        return back()->with('success', 'Quiz ended.');
    }

    // Leaderboard
    public function teacherLeaderboard(Request $request, Quiz $quiz)
    {
        abort_unless($quiz->teacher_id === $request->user()->id, 403);

        $quiz->load('questions');

        $maxScore = (int) $quiz->questions->sum('points');

        $attempts = QuizAttempt::query()
            ->where('quiz_id', $quiz->id)
            ->whereNotNull('submitted_at')
            ->with('student:id,name,email')
            ->orderByDesc('score')
            ->orderBy('submitted_at')
            ->limit(100)
            ->get();

        return view('quizzes.leaderboard', compact('quiz', 'attempts', 'maxScore'));
    }

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
            'points' => ['required', 'integer', 'min:1', 'max:100'],

            'options' => ['nullable', 'array', 'max:10'],
            'options.*' => ['nullable', 'string', 'max:255'],
            'correct_index' => ['nullable', 'integer', 'min:0', 'max:10'],
        ]);

        $question->update([
            'type' => $validated['type'],
            'text' => $validated['text'],
            'points' => (int) $validated['points'],
        ]);

        // SHORT: remove options
        if ($validated['type'] === 'short') {
            $question->options()->delete();
            return redirect()->route('quizzes.manage', $quiz)->with('success', 'Question updated successfully.');
        }

        // TF: force options
        if ($validated['type'] === 'tf') {
            $correctIndex = (int) ($validated['correct_index'] ?? 0);
            if ($correctIndex < 0 || $correctIndex > 1)
                $correctIndex = 0;

            $question->options()->delete();
            foreach (['True', 'False'] as $i => $optText) {
                $question->options()->create([
                    'text' => $optText,
                    'is_correct' => ($i === $correctIndex),
                ]);
            }

            return redirect()->route('quizzes.manage', $quiz)->with('success', 'Question updated successfully.');
        }

        // MCQ: replace options
        $options = collect($validated['options'] ?? [])
            ->map(fn($v) => trim((string) $v))
            ->filter(fn($v) => $v !== '')
            ->values();

        if ($options->count() < 2) {
            return back()->withErrors(['options' => 'At least 2 options are required for MCQ.'])->withInput();
        }

        if ($options->count() > 6) {
            $options = $options->take(6)->values();
        }

        $correctIndex = (int) ($validated['correct_index'] ?? 0);
        if ($correctIndex < 0 || $correctIndex >= $options->count())
            $correctIndex = 0;

        $question->options()->delete();

        foreach ($options as $i => $optText) {
            $question->options()->create([
                'text' => $optText,
                'is_correct' => ($i === $correctIndex),
            ]);
        }

        return redirect()->route('quizzes.manage', $quiz)->with('success', 'Question updated successfully.');
    }

    // ✅ Delete Question
    public function teacherDeleteQuestion(Request $request, Quiz $quiz, Question $question)
    {
        $teacher = $request->user();

        abort_unless($quiz->teacher_id === $teacher->id, 403);
        abort_unless($question->quiz_id === $quiz->id, 404);

        if ($quiz->status === 'active') {
            return back()->withErrors(['delete' => 'Stop the quiz before deleting questions.']);
        }

        $question->options()->delete();
        $question->delete();

        return back()->with('success', 'Question deleted.');
    }

    /**
     * Manual grading list
     */
    public function teacherGradingIndex(Request $request, Quiz $quiz)
    {
        abort_unless($quiz->teacher_id === $request->user()->id, 403);

        $attempts = QuizAttempt::query()
            ->where('quiz_id', $quiz->id)
            ->with('student:id,name,email')
            ->latest('submitted_at')
            ->paginate(25);

        return view('quizzes.grading.index', compact('quiz', 'attempts'));
    }

    /**
     * Manual grading detail
     */
    public function teacherGradingShow(Request $request, Quiz $quiz, QuizAttempt $attempt)
    {
        abort_unless($quiz->teacher_id === $request->user()->id, 403);
        abort_unless($attempt->quiz_id === $quiz->id, 404);

        $attempt->load([
            'student:id,name,email',
            'answers.question.options',
            'answers.selectedOption',
        ]);

        return view('quizzes.grading.show', compact('quiz', 'attempt'));
    }

    /**
     * Save manual grading (short answers)
     */
    public function teacherGradeAttempt(Request $request, Quiz $quiz, QuizAttempt $attempt)
    {
        abort_unless($quiz->teacher_id === $request->user()->id, 403);
        abort_unless($attempt->quiz_id === $quiz->id, 404);

        $attempt->load('answers.question');

        $data = $request->validate([
            'grades' => ['required', 'array'],
            'grades.*' => ['required', 'in:0,1'],
        ]);

        foreach ($attempt->answers as $ans) {
            if (($ans->question?->type ?? '') !== 'short') {
                continue;
            }

            $val = (int) ($data['grades'][$ans->id] ?? 0);
            $ans->update(['is_correct' => (bool) $val]);
        }

        // recompute score (only correct answers count)
        $score = 0;
        foreach ($attempt->answers as $ans) {
            $q = $ans->question;
            if (!$q)
                continue;

            if ($ans->is_correct === true) {
                $score += (int) ($q->points ?? 1);
            }
        }

        $attempt->update(['score' => $score]);

        return back()->with('success', 'Grading saved & score updated.');
    }

    /* ============================
     * STUDENT SIDE
     * ============================ */

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

        // calculate remaining seconds
        $durationSeconds = $quiz->duration ? ((int) $quiz->duration * 60) : null;

        $remainingSeconds = null;
        if ($durationSeconds !== null && $attempt->started_at) {
            $endAt = $attempt->started_at->copy()->addSeconds($durationSeconds);
            $remainingSeconds = max(0, now()->diffInSeconds($endAt, false));
        }

        if ($remainingSeconds !== null && $remainingSeconds <= 0) {
            return redirect()->route('quizzes.result', $quiz)
                ->withErrors(['time' => 'Time is up.']);
        }

        return view('quizzes.play', compact('quiz', 'attempt', 'remainingSeconds'));
    }

    public function studentSubmit(Request $request, Quiz $quiz)
    {
        $student = $request->user();

        $attempt = QuizAttempt::query()
            ->where('quiz_id', $quiz->id)
            ->where('student_id', $student->id)
            ->firstOrFail();

        abort_if($attempt->submitted_at, 403);

        $quiz->load(['questions.options']);

        // TIMER ENFORCEMENT (accept late submit for better UX)
        if ($quiz->duration && $attempt->started_at) {
            $endAt = $attempt->started_at->copy()->addMinutes((int) $quiz->duration);
            // If you want to reject late submit: abort_if(now()->greaterThan($endAt), 403);
        }

        // Dynamic validation based on question type
        $rules = [];
        foreach ($quiz->questions as $q) {
            if (($q->type ?? 'mcq') === 'short') {
                $rules["short.{$q->id}"] = ['required', 'string', 'max:2000'];
            } else {
                $rules["answers.{$q->id}"] = ['required', 'integer'];
            }
        }

        $data = $request->validate($rules);

        $score = 0;

        foreach ($quiz->questions as $question) {
            $type = $question->type ?? 'mcq';

            if ($type === 'short') {
                $txt = trim((string) ($data['short'][$question->id] ?? ''));

                QuizAnswer::updateOrCreate(
                    [
                        'attempt_id' => $attempt->id,
                        'question_id' => $question->id,
                    ],
                    [
                        'selected_option_id' => null,
                        'short_answer' => $txt,
                        // short answer needs manual grading
                        'is_correct' => null,
                    ]
                );

                continue;
            }

            $selectedOptionId = (int) ($data['answers'][$question->id] ?? 0);
            $option = $question->options->firstWhere('id', $selectedOptionId);
            $isCorrect = $option ? (bool) $option->is_correct : false;

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
                $score += (int) $question->points;
            }
        }

        $attempt->update([
            'submitted_at' => now(),
            'score' => $score,
        ]);

        return redirect()->route('quizzes.result', $quiz);
    }

    public function studentResult(Request $request, Quiz $quiz)
    {
        $student = $request->user();

        $attempt = QuizAttempt::query()
            ->where('quiz_id', $quiz->id)
            ->where('student_id', $student->id)
            ->with([
                'answers.question.options',
                'answers.selectedOption',
                'quiz.teacher:id,name',
            ])
            ->firstOrFail();

        $quiz->load('questions');
        $maxScore = (int) $quiz->questions->sum('points');

        return view('quizzes.result', compact('quiz', 'attempt', 'maxScore'));
    }

    // Student quiz history page
    public function studentHistory(Request $request)
    {
        $student = $request->user();

        $attempts = QuizAttempt::query()
            ->where('student_id', $student->id)
            ->with([
                'quiz.teacher:id,name',
                'quiz.questions:id,quiz_id,points',
            ])
            ->latest('id')
            ->get()
            ->map(function ($a) {
                $maxScore = (int) ($a->quiz?->questions?->sum('points') ?? 0);
                $a->max_score = $maxScore;
                return $a;
            });

        return view('student.quizzes.history', compact('attempts'));
    }

    public function studentAttemptShow(Request $request, QuizAttempt $attempt)
    {
        $student = $request->user();
        abort_unless($attempt->student_id === $student->id, 403);

        $attempt->load([
            'quiz.teacher:id,name',
            'quiz.questions.options',
            'answers.question.options',
            'answers.selectedOption',
        ]);

        $maxScore = (int) ($attempt->quiz?->questions?->sum('points') ?? 0);

        // helpful: compute correct option per question (for mcq/tf)
        $correctByQuestion = [];
        foreach (($attempt->quiz?->questions ?? []) as $q) {
            if (in_array($q->type, ['mcq', 'tf'])) {
                $correctByQuestion[$q->id] = $q->options->firstWhere('is_correct', true);
            }
        }

        return view('student.quizzes.show', compact('attempt', 'maxScore', 'correctByQuestion'));
    }

}
