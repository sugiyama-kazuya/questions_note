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
    public function getCountLikesAttribute(): int
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
    public function isLikedBy(int $user_id = null): bool
    {
        return $user_id
            ? (bool) $this->likes()->where('users.id', $user_id)->count() : false;
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
     * @param $exercise_books object
     * @param $likes Boolean DBから取得時にlikesテーブルも取得しているか否か
     * @return object
     */
    public function filteringRequiredData($exercise_books, int $user_id = null): object
    {
        // いいねの数といいねがされているか否かの変数を代入
        $exercise_books = $exercise_books->map(function ($data) use ($user_id) {
            $exercise_books_data = $data;
            $exercise_books_data['favolite_count'] = $data['likes']->count();
            $exercise_books_data['is_liked_by'] = $data->isLikedBy($user_id);
            return $exercise_books_data;
        });

        //        必要なデータの絞り込み
        $exercise_books = $exercise_books->map(function ($data) {
            return $data->only(['id', 'updated_at', 'user_id', 'exercise_books_name_id', 'user', 'exerciseBooksName', 'favolite_count', 'is_liked_by']);
        });

        return $exercise_books;
    }

    /**
     * 使用するデータを取得
     *
     * @return object
     */
    public function getExerciseBookData(): object
    {
        return $this->with(['user:id,name,profile_img', 'exerciseBooksName:id,name', 'likes']);
    }

    /**
     * S3からユーザー画像のURLを取得
     *
     * @param [object] $data
     * @return object
     */
    public function addProfileUrl($data): object
    {
        return $data->map(function ($item) {
            $data = $item;
            $data['user']['profile_img'] = $item->user->awsUrlFetch($item->user->profile_img);
            return $data;
        });
    }
}
