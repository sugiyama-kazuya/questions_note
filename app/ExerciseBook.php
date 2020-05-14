<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExerciseBook extends Model
{
    protected $table = 'exercise_books';

    protected $fillable = [
        'exercise_books_name_id', 'problem_id', 'user_id','created_at'
    ];

    public function user() :BelongsTo
    {
        return $this->belongsTo('App\User');
    }

    public function problem() :BelongsTo
    {
        return $this->belongsTo('App\Problem');
    }

    public function exerciseBooksName() :BelongsTo
    {
        return $this->belongsTo('App\ExerciseBookName');
    }
}
