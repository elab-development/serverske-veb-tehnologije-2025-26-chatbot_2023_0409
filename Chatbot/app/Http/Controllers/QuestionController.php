<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuestionResource;
use App\Models\Question;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Cache;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Question::query();

        if ($request->category_id)
        {
            $query->where('category_id', $request->category_id);
        }

        if ($request->keyword) 
        {
            $query->where('question', 'like', '%' . $request->keyword . '%');
        }
        
        $sort = $request->sort ?? 'id';
        $order = $request->order ?? 'asc';

        $query->orderBy($sort, $order);

        $perPage = $request->per_page ?? 3;

        $cacheKey = 'questions_' . md5(json_encode($request->all()));

        $questions = Cache::remember($cacheKey, 60, function () use ($query, $perPage) {
            return $query->paginate($perPage)->toArray();});
        
        return response()->json($questions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return Question::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Question::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $question = Question::findOrFail($id);
        $question->update($request->all());
        return $question;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Question::destroy($id);
        return response()->json(['message' => 'Deleted']);
    }

    public function questionByCategory($id)
    {
        return Question::where('category_id', $id)->get();
    }

    public function search(Request $request)
    {
        $query = Question::query();

        if ($request->keyword) 
        {
            $query->where('question', 'like', '%' . $request->keyword . '%');
        }

        if ($request->category_id) 
        {
            $query->where('category_id', $request->category_id);
        }

        return $query->get();
    }

    public function questionsDetails()
    {
        return Question::join('categories', 'questions.category_id', '=', 'categories.id')
            ->join('sports', 'questions.sport_id', '=', 'sports.id')
            ->join('answers', 'questions.id', '=', 'answers.question_id')
            ->select(
                'questions.id',
                'questions.question',
                'categories.name as category',
                'sports.name as sport',
                'answers.answer'
            )
            ->get();
    }

    public function chatbot(Request $request)
    {
        $userQuestion = strtolower($request->question);

        $words = preg_split('/\s+/', $userQuestion);

        $questions = Question::with('answer')->get();

        $bestQuestion = null;
        $bestScore = 0;

        foreach ($questions as $question) {

            $questionText = strtolower($question->question);
            $keywords = strtolower($question->keywords ?? '');
            $keywords = str_replace(',', ' ', $keywords);

            $score = 0;

            foreach ($words as $word) {

                if (strlen($word) < 3) {
                    continue;
                }

                if (str_contains($questionText, $word)) {
                    $score += 1;
                }

                if (str_contains($keywords, $word)) {
                    $score += 2;
                }
            }

            if ($score > $bestScore) {
                $bestScore = $score;
                $bestQuestion = $question;
            }
        }

        if (!$bestQuestion || !$bestQuestion->answer) {
            return response()->json([
                'message' => 'Nisam pronašao odgovor na vaše pitanje.'
            ], 404);
        }

        return response()->json([
            'question' => $bestQuestion->question,
            'answer' => $bestQuestion->answer->answer
        ]);
    }
}
