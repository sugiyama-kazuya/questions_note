<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable
{
    use Notifiable;

    const ID_LENGTH = 10;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'profile_img'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'followers', 'followings', 'email_verified_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // public function getUrlAttribute()
    // {
    //     return $this->attributes['profile_img'];
    // }

    /**
     * ランダムなid値を作成
     *
     * @return integer
     */
    public function getRandomId(): string
    {
        $characters = array_merge(
            range(0, 9),
            range('a', 'z'),
            range('A', 'Z'),
            ['-', '_']
        );

        $length = count($characters);
        $id = "";

        for ($i = 0; $i < self::ID_LENGTH; $i++) {
            $id .= $characters[random_int(0, $length - 1)];
        };

        return $id;
    }

    /**
     * フォロワー
     *
     * @return BelongsToMany
     */
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany('App\User', 'follows', 'followee_id', 'follower_id')->withTimestamps();
    }

    /**
     * フォロー
     *
     * @return BelongsToMany
     */
    public function followings(): BelongsToMany
    {
        return $this->belongsToMany('App\User', 'follows', 'follower_id', 'followee_id')->withTimestamps();
    }

    /**
     * 対象のユーザーを取得
     *
     * @param integer $user_id
     * @return
     */
    public function currentUser(int $user_id)
    {
        return $this->find($user_id);
    }

    /**
     * ログインユーザーが対象のユーザーをフォローしているか否か
     *
     * @param integer $user_id
     * @return boolean
     */
    public function isFollowedBy(int $user_id = null): bool
    {
        return $user_id
            ?  (bool) $this->followers()->where('users.id', $user_id)->count()
            : false;
    }

    /**
     * フォロワーの数を取得
     *
     * @return integer
     */
    public function getFollowersCountAttribute(): int
    {
        return $this->followers->count();
    }

    /**
     * フォローの数を取得
     *
     * @return integer
     */
    public function getFollowingsCountAttribute(): int
    {
        return $this->followings->count();
    }

    /**
     * プロフィール画面の表示の為の必須の情報を取得
     *
     * @param integer $user_id
     * @return object
     */
    public function getProfileRequiredData(int $user_id): object
    {
        $current_user = $this->currentUser($user_id);

        if ($current_user->profile_img) {
            $file_path = $current_user->awsUrlFetch($current_user->profile_img);
            $current_user['followers_count'] = $current_user->followers_count;
            $current_user['followings_count'] = $current_user->followings_count;
            $current_user['profile_img'] = $file_path;
            return $current_user;
        } else {
            $current_user['followers_count'] = $current_user->followers_count;
            $current_user['followings_count'] = $current_user->followings_count;
            return $current_user;
        }
    }

    public function awsUrlFetch(string $file_path = null): string
    {
        return $file_path ? Storage::cloud()->url($file_path) : "";
    }
}
