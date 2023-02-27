<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $fillable = [
        'quiz_id', 'description', 'mandatory',
    ];
    
    public function answers()
    {
        return $this->hasMany(Answer::class); 
    }
}
