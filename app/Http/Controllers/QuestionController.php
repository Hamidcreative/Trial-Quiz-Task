<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request; 
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;



class QuestionController extends Controller
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
        $question = Question::with('answers')->get();
        return response()->json([
            'success' => true,
            'error'=>null, 
            'data' => $question
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
        $data = $request->only('quiz_id', 'description','mandatory');
        $validator = Validator::make($data, [
            'quiz_id' => 'required',
            'description' => 'required', 
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['success'=>false,'error' => $validator->messages()], 200);
        }

        //Request is valid, create new quiz
        $question = Question::create([
            'quiz_id' => $request->quiz_id,
            'description' => $request->description,
            'mandatory' => $request->mandatory, 
        ]);

        //Question created, return success response
        return response()->json([
            'success' => true,
            'error'=>null,
            'message' => 'Question created successfully',
            'data' => $question
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Question::with('answers')->find($id);
        if (!$data) {
            return response()->json([
                'success' => false,
                'errors'=>['message' => 'Sorry, Question not found.']
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
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Question $question)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Question::find($id);
        if (!$data) {
            return response()->json([
                'success' => false,
                'errors'=>['message' => 'Sorry, Question not found.']
            ], 400);
        }
        $data->delete();
        return response()->json([
            'success' => true,
            'errors'=>null,
            'message' => 'Question deleted successfully'
        ], Response::HTTP_OK);
    }
}
