<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SportsController;
use App\Http\Controllers\SportController;


// JAVNE RUTE - GOST

Route::get('/questions', [QuestionController::class, 'index']);
Route::get('/questions/details', [QuestionController::class, 'questionsDetails']);
Route::get('/questions/{question}', [QuestionController::class, 'show']);

Route::get('/answers', [AnswerController::class, 'index']);
Route::get('/answers/{answer}', [AnswerController::class, 'show']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);

Route::get('/questions/category/{id}', [QuestionController::class, 'questionByCategory']);
Route::post('/questions/search', [QuestionController::class, 'search']);
Route::post('/chatbot', [QuestionController::class, 'chatbot']);

Route::get('/sports/team/{name}', [SportsController::class, 'team']);

Route::apiResource('sports', SportController::class);


// REGISTRACIJA I LOGIN

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


// USER

Route::middleware(['auth:sanctum', 'role:user'])->group(function () {

    Route::post('/questions', [QuestionController::class, 'store']);
    Route::put('/questions/{question}', [QuestionController::class, 'update']);
    Route::patch('/questions/{question}', [QuestionController::class, 'update']);

    Route::post('/answers', [AnswerController::class, 'store']);
    Route::put('/answers/{answer}', [AnswerController::class, 'update']);
    Route::patch('/answers/{answer}', [AnswerController::class, 'update']);
});


// ADMINISTRATOR

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {

    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::patch('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);

    Route::delete('/questions/{question}', [QuestionController::class, 'destroy']);

    Route::delete('/answers/{answer}', [AnswerController::class, 'destroy']);

    Route::delete('/answers/question/{id}', [AnswerController::class, 'deleteByQuestion']);
});


// AUTENTIFIKACIJA

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});