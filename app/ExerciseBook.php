<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;

class ExerciseBook extends Model
{

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $table = 'exercise_books';

    public $timestamps = false;

    protected $dateFormat = "Y/m/d";

    protected $fillable = [
        'name', 'user_id', 'category_id', 'created_at', 'updated_at'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo('App\User');
    }

    public function problem(): HasMany
    {
        return $this->hasMany('App\Problem');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo('App\Category');
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
     * ログインユーザーの問題集を取得
     *
     * @return Object
     */
    public function getLoginUserExerciseBookAttribute()
    {
        return $this->where('user_id', Auth::id())->get(['id', 'name']);
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
     * 問題カードの表示に必要な基本データ
     *
     * @return Object
     */
    public function exerciseBookCardRequiredData(): Object
    {
        return $this->with(['user:id,name,profile_img', 'likes', 'category']);
    }

    /**
     * お気に入り情報の追加
     *
     * @param $exercise_books
     * @param integer $user_id
     * @return object
     */
    public function addFavoriteInfo($exercise_books, int $user_id = null)
    {
        if ($user_id) {
            // ログインしている場合
            return $exercise_books = $exercise_books->map(function ($data) use ($user_id) {
                $exercise_books_data = $data;
                $exercise_books_data['favolite_count'] = $data['likes']->count();
                $exercise_books_data['is_liked_by'] = $data->isLikedBy($user_id);
                return $exercise_books_data;
            });
        } else {
            // ログインしていない場合
            return $exercise_books = $exercise_books->map(function ($data) {
                $exercise_books_data = $data;
                $exercise_books_data['favolite_count'] = $data['likes']->count();
                return $exercise_books_data;
            });
        }
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
        if ($user_id) {
            return $exercise_books = $exercise_books->map(function ($data) {
                return $data->only(['id', 'updated_at', 'name', 'user_id', 'user', 'favolite_count', 'is_liked_by', 'profile_img', 'category']);
            });

            return $exercise_books;
        } else {
            return $exercise_books = $exercise_books->map(function ($data) {
                return $data->only(['id', 'updated_at', 'name', 'user_id', 'user', 'favolite_count', 'profile_img', 'category']);
            });

            return $exercise_books;
        }
    }

    /**
     * ログインユーザーの問題集を取得し、
     * リクエストから送られてきた問題集があれば取得、なければ登録
     *
     * @param Object $request
     * @param Int $category_id
     * @return Object
     */
    public function fetchOrRegister(Object $request, Int $category_id)
    {
        return $this->where('user_id', Auth::id())->firstOrCreate(
            ['name' => $request->exerciseBook],
            [
                'name' => $request->exerciseBook,
                'user_id' => Auth::id(),
                'category_id' => $category_id
            ]
        );
    }

    /**
     * S3からユーザー画像のURLを取得
     *
     * @param [Object] $exercise_books
     * @return object
     */
    public function addProfileUrl(object $exercise_books): object
    {
        return $exercise_books->map(function ($exercise_book) {
            $data = $exercise_book;
            $data['profile_img'] = $exercise_book->user->awsUrlFetch($exercise_book->user->profile_img);
            return $data;
        });
    }
}
