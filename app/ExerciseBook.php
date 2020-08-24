<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
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
        'name', 'user_id', 'created_at', 'updated_at'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo('App\User');
    }

    public function problem(): HasMany
    {
        return $this->hasMany('App\Problem');
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
     * 問題カードの表示の為の基本データ取得
     *
     * @return Object
     */
    public function scopeBaseData($query)
    {
        return $query->with(['user:id,name,profile_img', 'likes', 'problem']);
    }

    /**
     * お気に入り情報の追加
     *
     * @param $exercise_books
     * @return object
     */
    public function addFavoriteInfo($exercise_books)
    {
        $login_user_id = Auth::id();

        // ログインしていない場合はお気に入りの有無は表示しない
        if ($login_user_id) {
            return $exercise_books = $exercise_books->map(function ($data) use ($login_user_id) {
                $exercise_book = $data;
                $exercise_book['favorite_count'] = $data['likes']->count();
                $exercise_book['is_liked_by'] = $data->isLikedBy($login_user_id);
                return $exercise_book;
            });
        } else {
            return $exercise_books = $exercise_books->map(function ($data) {
                $exercise_book = $data;
                $exercise_book['favorite_count'] = $data['likes']->count();
                return $exercise_book;
            });
        }
    }

    /**
     * 問題カードを表示する為の必要なデータの絞り込み
     *
     * @param $exercise_books object
     * @param $likes Boolean DBから取得時にlikesテーブルも取得しているか否か
     * @return object
     */
    public function filteringCardData($exercise_books): object
    {
        $login_user_id = Auth::id();

        if ($login_user_id) {
            return $exercise_books = $exercise_books->map(function ($data) {
                return $data->only(['id', 'problem_update_at', 'name', 'user_id', 'user', 'favorite_count', 'is_liked_by', 'profile_img']);
            });

            return $exercise_books;
        } else {
            return $exercise_books = $exercise_books->map(function ($data) {
                return $data->only(['id', 'problem_update_at', 'name', 'user_id', 'user', 'favorite_count', 'profile_img']);
            });
        }
    }

    /**
     * ログインユーザーの問題集を取得し、
     * リクエストから送られてきた問題集があれば取得、なければ登録
     *
     * @param Object $request
     * @return Object
     */
    public function fetchOrRegister(Object $request)
    {
        return $this->where('user_id', Auth::id())->firstOrCreate(
            ['name' => $request->exerciseBook],
            [
                'name' => $request->exerciseBook,
                'user_id' => Auth::id(),
            ]
        );
    }

    /**
     * S3からユーザー画像のURLを取得
     *
     * @param [Object] $exercise_books
     * @return object
     */
    public function addProfileUrl($exercise_books)
    {
        return $exercise_books->map(function ($exercise_book) {
            $data = $exercise_book;
            $data['profile_img'] = $exercise_book->user->awsUrlFetch($exercise_book->user->profile_img);
            return $data;
        });
    }

    /**
     * 自身がお気に入り登録している問題集を取得
     *
     * @param [Object] $exercise_books
     * @param [int] $user_id
     * @return void
     */
    public function fetchOwnFavoriteRegisterdExerciseBooks($exercise_books): object
    {
        return $exercise_books->filter(function ($exercise_book) {
            return $exercise_book->is_liked_by === true;
        })->values();
    }

    /**
     *お気に入りの数で降順に並び変える
     *
     * @param [type] $exercise_books
     * @return object
     */
    public function favoriteCountDesc($exercise_books): object
    {
        return $exercise_books->sortByDesc(function ($exercise_book) {
            return $exercise_book['favorite_count'];
        })->values();
    }

    /**
     * 問題の更新日を降順に並び変える
     *
     * @param [type] $exercise_books
     * @return void
     */
    public function problemUpdateDateDesc($exercise_books)
    {
        return $exercise_books->sortByDesc(function ($exercise_book) {
            return $exercise_book['problem_update_at'];
        })->values();
    }

    /**
     * 問題の更新日を取得
     *
     * @param [type] $exercise_books
     * @return void
     */
    public function addProblemUpdateDate($exercise_books)
    {
        return $exercise_books->map(function ($exercise_book) {
            if ($exercise_book->problem->first()) {
                $data = $exercise_book;
                $data['problem_update_at'] = $exercise_book->problem->last()->updated_at;
                return $data;
            } else {
                return $exercise_book;
            }
        });
    }
}
