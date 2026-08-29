<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Answer::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return Answer::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Answer::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $answer = Answer::findOrFail($id);
        $answer->update($request->all());
        return $answer;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Answer::destroy($id);
        return response()->json(['message' => 'Deleted']);
    }

    public function deleteByQuestion($id)
    {
        Answer::where('question_id', $id)->delete();
        return response()->json(['message' => 'Answers deleted']);
    }
}
