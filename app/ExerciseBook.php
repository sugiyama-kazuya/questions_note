<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExerciseBook extends Model
{
    protected $table = 'exercise_books';

    protected $fillable = [
        'name', 'problem_id', 'user_id'
    ];
}
