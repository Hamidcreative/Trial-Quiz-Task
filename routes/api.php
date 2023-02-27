<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AnswerController;

Route::post('login', [ApiController::class, 'authenticate']);
Route::post('register', [ApiController::class, 'register']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('logout', [ApiController::class, 'logout']);
    Route::get('user', [ApiController::class, 'get_user']);
    
    //Quizzes 
    Route::get('quiz', [QuizController::class, 'index']);
    Route::get('quiz/{id}', [QuizController::class, 'show']);
    Route::post('quiz/create', [QuizController::class, 'store']);
    Route::put('quiz/update/{quiz}',  [QuizController::class, 'update']);
    Route::delete('quiz/{id}', [QuizController::class, 'destroy']);
    
    //Submit Quiz
    Route::post('quiz/submit', [QuizController::class, 'submit_quiz']);
    
    // Questions 
    Route::get('questions', [QuestionController::class, 'index']);
    Route::get('questions/{id}', [QuestionController::class, 'show']);
    Route::post('questions/create', [QuestionController::class, 'store']);
    Route::put('questions/update/{quiz}',  [QuestionController::class, 'update']);
    Route::delete('questions/{id}', [QuestionController::class, 'destroy']);
    
    // Answers 
    Route::get('answers', [AnswerController::class, 'index']);
    Route::get('answers/{id}', [AnswerController::class, 'show']);
    Route::post('answers/create', [AnswerController::class, 'store']);
    Route::put('answers/update/{quiz}',  [AnswerController::class, 'update']);
    Route::delete('answers/{id}', [AnswerController::class, 'destroy']);
    
});