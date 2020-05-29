<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'followers', 'followings'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

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
     * @return object
     */
    public function currentUser(int $user_id): object
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
        $current_user['followers_count'] = $current_user->followers_count;
        $current_user['followings_count'] = $current_user->followings_count;
        return $current_user;
    }
}
