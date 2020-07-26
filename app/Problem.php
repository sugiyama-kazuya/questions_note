<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class Problem extends Model
{
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'content', 'answer', 'user_id', 'exercise_book_id', 'url'
    ];

    protected $dateFormat = "Y/m/d";

    public function exerciseBook(): BelongsTo
    {
        return $this->belongsTo('App\ExerciseBook');
    }

    protected $hidden = [
        'created_at', 'updated_at', 'user_id', 'exercise_book_id'
    ];

    /**
     * 日付のフォーマットを変更
     *
     * @param $value
     * @return string
     */
    public function getCreatedAtAttribute($value): string
    {
        return Carbon::parse($value)->format("Y/m/d");
    }


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

    /**
     * user_idと問題数を追加する
     *
     * @param [object] $problems
     * @return object
     */
    public function addUserIdAndProblemsCount($problems)
    {
        $user_id = $problems->map(function ($problem) {
            return $problem->user_id;
        });

        $problems_count = $problems->count('id');
        $problems = Arr::add(['problems' => $problems], 'problems_count', $problems_count);
        $problems['user_id'] = $user_id[0];

        return $problems;
    }
}
