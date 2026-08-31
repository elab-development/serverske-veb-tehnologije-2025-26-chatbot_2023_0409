<?php

namespace App\Http\Controllers;

use App\Models\Sport;
use Illuminate\Http\Request;

class SportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Sport::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return Sport::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Sport::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $sport = Sport::findOrFail($id);
        $sport->update($request->all());

        return $sport;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Sport::destroy($id);

        return response()->json([
            'message' => 'Deleted'
        ]);
    }
}
