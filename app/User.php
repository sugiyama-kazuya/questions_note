<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use App\Traits\RelatedToFilePathS3;
use App\Notifications\PasswordResetNotification;

class User extends Authenticatable
{
    use Notifiable;
    use RelatedToFilePathS3;

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
        'password', 'remember_token', 'email_verified_at',
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

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordResetNotification($token, $this->email));
    }

    /**
     * 対象のユーザーを取得
     *
     * @param integer $user_id
     * @return
     */
    public function currentUser($user_id)
    {
        return $this->findOrFail($user_id);
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
     *
     */
    public function profileBaseData($user_id)
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

    /**
     * プロフィール画像を追加する
     *
     * @param [type] $users
     * @return object
     */
    public function addProfileUrl($users)
    {
        return $users = $users->map(function ($user) {
            $user = $user;
            $user['profile_img'] = $this->awsUrlFetch($user->profile_img);
            return $user;
        });
    }

    /**
     * フォロー有無のプロパティを追加
     *
     * @param [object]] $users
     * @param [int] $user_id
     * @return void
     */
    public function fetchIsFollowedBy($users, $user_id)
    {
        return $users->map(function ($user) use ($user_id) {
            $user = $user;
            $user['is_followed_by'] = $user->isFollowedBy($user_id);
            return $user;
        });
    }

    /**
     * プロフィールの更新
     *
     * @param [object] $request
     * @param [string] $file_path
     * @return void
     */
    public function profileUpdate($request, $file_path = null)
    {
        if ($file_path) {
            $this->findOrFail(Auth::id())->fill([
                'name' => $request->name,
                'email' => $request->email,
                'profile_img' => $file_path
            ])->save();
        } else {
            $this->findOrFail(Auth::id())->fill([
                'name' => $request->name,
                'email' => $request->email,
            ])->save();
        }
    }

    /**
     *ログインユーザー以外のページを表示する時は、フォローの有無が必要となるので取得する
     *
     * @param [type] $user
     * @param [type] $user_id
     * @return void
     */
    public function getLoginUserOtherThanFollowPresence($user, $user_id)
    {
        $login_user_id = Auth::id();
        if ($login_user_id !== (int) $user_id) {
            $user['is_followed_by'] = $user->isFollowedBy($login_user_id);
        }
        return $user;
    }
}
