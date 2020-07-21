<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

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

    /**
     * 問題と答えの新規登録
     *
     * @param [object]] $request
     * @param [object] $exercise_book
     * @return void
     */
    public function newRegister($request, $exercise_book)
    {
        $this->fill([
            'content' => $request->problem,
            'answer' => $request->answer,
            'url' => $request->url,
            'user_id' => Auth::id(),
            'exercise_book_id' => $exercise_book->id,
        ])->save();
    }

    /**
     * 問題の編集
     *
     * @param [type] $problem_id
     * @param [type] $request
     * @param [type] $exercise_book
     * @return void
     */
    public function problemUpdate($problem_id, $request, $exercise_book)
    {
        $this->find($problem_id)->fill([
            'content' => $request->problem,
            'answer' => $request->answer,
            'url' => $request->url,
            'user_id' => Auth::id(),
            'exercise_book_id' => $exercise_book->id,
        ])->update();
    }
}
