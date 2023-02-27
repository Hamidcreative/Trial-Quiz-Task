<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quizeresults extends Model
{
    use HasFactory;
    protected $table = 'quiz_results';
    protected $fillable = [
        'user_id', 'quiz_id','correctAnswers','incorrectAnswers','totalQuestions','score'
    ]; 
}
