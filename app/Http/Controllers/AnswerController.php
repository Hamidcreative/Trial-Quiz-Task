<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use Illuminate\Http\Request; 
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class AnswerController extends Controller
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
        $answer = Answer::get();
        return response()->json([
            'success' => true,
            'error'=>null, 
            'data' => $answer
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
        $data = $request->only('question_id', 'description','status');
        $validator = Validator::make($data, [
            'question_id' => 'required',
            'description' => 'required', 
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['success'=>false,'error' => $validator->messages()], 200);
        }

        //Request is valid, create new answer
        $answer = Answer::create([
            'question_id' => $request->question_id,
            'description' => $request->description,
            'status' => $request->status, 
        ]);

        //answer created, return success response
        return response()->json([
            'success' => true,
            'error'=>null,
            'message' => 'Answers created successfully',
            'data' => $answer
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Answer::find($id);
        if (!$data) {
            return response()->json([
                'success' => false,
                'errors'=>['message' => 'Sorry, Answer not found.']
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
     * @param  \App\Models\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function edit(Answer $answer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Answer $answer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Answer::find($id);
        if (!$data) {
            return response()->json([
                'success' => false,
                'errors'=>['message' => 'Sorry, Answer not found.']
            ], 400);
        }
        $data->delete();
        return response()->json([
            'success' => true,
            'errors'=>null,
            'message' => 'Answer deleted successfully'
        ], Response::HTTP_OK);
    }
}
