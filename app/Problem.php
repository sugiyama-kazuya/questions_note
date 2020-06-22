<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Problem extends Model
{
    protected $fillable = [
        'content', 'answer', 'user_id', 'exercise_book_id', 'url'
    ];

    public function exerciseBook(): BelongsTo
    {
        return $this->belongsTo('App\ExerciseBook');
    }

    protected $hidden = [
        'created_at', 'user_id', 'exercise_book_id'
    ];
}
