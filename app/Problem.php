<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Problem extends Model
{
    protected $fillable = [
        'content', 'answer', 'user_id', 'exercise_book_id', 'url'
    ];
}
