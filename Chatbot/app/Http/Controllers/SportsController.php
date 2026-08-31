<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class SportsController extends Controller
{
    public function team($name)
    {
        $response = Http::get('https://www.thesportsdb.com/api/v1/json/123/searchteams.php',
            [
                't' => $name
            ]
        );

        return response()->json($response->json());
    }
}
