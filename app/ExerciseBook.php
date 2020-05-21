<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function problem(): BelongsTo
    {
        return $this->belongsTo('App\Problem');
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
     * 問題集のいいね数を取得
     *
     * @return integer
     */
    public function getCountLikesAttribute()
    {
        return $this->likes()->count();
    }

    /**
     * 問題集にいいねをしているか否か
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
     * 問題集を取得
     *
     * @param [type] $id
     * @return object
     */
    public function currentExerciseBook($id): object
    {
        return self::find($id);
    }
}
