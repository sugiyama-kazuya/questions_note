<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExerciseBook extends Model
{

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $table = 'exercise_books';

    public $timestamps = false;

    protected $dateFormat = "Y/m/d";

    protected $fillable = [
        'exercise_books_name_id', 'user_id', 'created_at', 'updated_at'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo('App\User');
    }

    public function problem(): HasMany
    {
        return $this->hasMany('App\Problem');
    }

    public function exerciseBooksName(): BelongsTo
    {
        return $this->belongsTo('App\ExerciseBookName');
    }

    /**
     * 日付のフォーマットを変更
     *
     * @param $value
     * @return string
     */
    public function getUpdatedAtAttribute($value): string
    {
        return Carbon::parse($value)->format("Y/m/d");
    }

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany('App\User', 'likes')->withTimestamps();
    }

    /**
     * 対象の問題集のいいね数を取得
     *
     * @return integer
     */
    public function getCountLikesAttribute()
    {
        return $this->likes()->count();
    }

    /**
     * 対象の問題集にいいねをしているか否か
     *
     * @param User|null $user
     * @param [type] $id
     * @return boolean
     */
    public function isLikedBy(?User $user): bool
    {
        return $user
            ? (bool) $this->likes()->where('users.id', $user->id)->count() : false;
    }

    /**
     * 対象の問題集を取得
     *
     * @param [type] $id
     * @return object
     */
    public function currentExerciseBook($id): object
    {
        return self::find($id);
    }

    /**
     * 問題カードを表示する為に必要なデータの絞り込み
     *
     * @return object
     */
    public function filteringRequiredData($exercise_books): object
    {
        //        必要なデータの絞り込み
        $exercise_books = $exercise_books->map(function ($data) {
            return $data->only(['id', 'updated_at', 'user_id', 'exercise_books_name_id', 'user', 'exerciseBooksName']);
        });

        //        重複している問題は一つに絞る
        $exercise_books = $exercise_books->unique(function ($item) {
            return $item['user_id'] . $item['exercise_books_name_id'];
        });

        return $exercise_books;
    }
}
