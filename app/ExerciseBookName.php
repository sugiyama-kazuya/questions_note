<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExerciseBookName extends Model
{
    protected $table = 'exercise_books_names';

    protected $fillable = [
        'name', 'user_id'
    ];
}
