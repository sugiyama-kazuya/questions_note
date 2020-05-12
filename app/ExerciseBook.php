<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExerciseBook extends Model
{
    protected $table = 'exercise_books';

    protected $fillable = [
        'exercise_books_name_id', 'problem_id', 'user_id'
    ];
}
