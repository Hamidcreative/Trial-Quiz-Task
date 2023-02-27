<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Answer;
use Illuminate\Http\Request; 
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;


class QuizController extends Controller
{
    protected $user;
 
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $quiz = Quiz::with(['questions' => function($q){
                    $q->select('*');
                },'questions.answers' => function($q){
                    $q->select('*');
                }])->get(); 
        return response()->json([
            'success' => true,
            'error'=>null, 
            'data' => $quiz
        ], Response::HTTP_OK);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only('title', 'description','publish');
        $validator = Validator::make($data, [
            'title' => 'required|string',
            'description' => 'required', 
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['success'=>false,'error' => $validator->messages()], 200);
        }

        //Request is valid, create new quiz
        $quiz = Quiz::create([
            'title' => $request->title,
            'description' => $request->description,
            'publish' => $request->publish, 
        ]);

        //quiz created, return success response
        return response()->json([
            'success' => true,
            'error'=>null,
            'message' => 'Quiz created successfully',
            'data' => $quiz
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function show($id) {  
        $data = Quiz::with(['questions' => function($q){
                    $q->select('*');
                },'questions.answers' => function($q){
                    $q->select('*');
                }])->find($id); 
        if (!$data) {
            return response()->json([
                'success' => false,
                'errors'=>['message' => 'Sorry, Quiz not found.']
            ], 400);
        }
        return response()->json([
            'success' => true,
            'error'=>null, 
            'data' => $data
        ], Response::HTTP_OK); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function edit(Quiz $quiz)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Quiz $quiz)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Quiz::find($id);
        if (!$data) {
            return response()->json([
                'success' => false,
                'errors'=>['message' => 'Sorry, Quiz not found.']
            ], 400);
        }
        $data->delete();
        return response()->json([
            'success' => true,
            'errors'=>null,
            'message' => 'Quiz deleted successfully'
        ], Response::HTTP_OK);
    }
    
    /**
     * Store a newly Submitted Quiz resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function submit_quiz(Request $request)
    {
        
         
        $data = $request->only('answer_id', 'quiz_id');
        $validator = Validator::make($data, [
            'quiz_id' => 'required',
            'answer_id' => 'required', 
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['success'=>false,'error' => $validator->messages()], 200);
        }
        $correctAnswers=0; 
        $incorrectAnswers=0; 
        $answers = $request->answer_id; 
        if($answers){
            foreach($answers as $answer){ 
                if(Answer::select('status')->where(['id'=>$answer,'status'=> true])->exists()){
                    $correctAnswers++;
                }else{
                    $incorrectAnswers++; 
                }
            }
        }
        
        $quiz = Quiz::with('questions')->find($request->answer_id);
        echo "<pre>";
        print_r($quiz);
               
        // // echo "<pre>";
        // echo '$countQuestions'.$countQuestions;
        // echo $correctAnswers;
        // echo "incorrectAnswers".$incorrectAnswers;
        exit;

        //Request is valid, create new quiz
        // $quiz = Quiz::create([
        //     'title' => $request->title,
        //     'description' => $request->description,
        //     'publish' => $request->publish, 
        // ]);

        //quiz created, return success response
        return response()->json([
            'success' => true,
            'error'=>null,
            'message' => 'Quiz Submitted successfully',
            'data' => $quiz
        ], Response::HTTP_OK);
    }

}
