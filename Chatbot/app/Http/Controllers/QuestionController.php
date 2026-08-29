<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuestionResource;
use App\Models\Question;
use Illuminate\Http\Request;

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

        $perPage = request('per_page', 3);
        $questions = $query->paginate($perPage);

        return QuestionResource::collection($questions);
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

    
}
