<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

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
        'password', 'remember_token', 'email_verified_at'
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

    /**
     * s3からプロフィール画像のurlを取得
     *
     * @param string $file_path
     * @return string
     */
    public function awsUrlFetch(string $file_path = null): string
    {
        return $file_path ? Storage::cloud()->url($file_path) : "";
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
     * S3にファイルを保存する。
     *
     * @param [type] $file
     * @param [type] $file_path
     * @return void
     */
    public function saveFileToS3($file, $file_path)
    {
        // 第３引数の名前で第2引数を保存する
        Storage::cloud()->putFileAs('', $file, $file_path, 'public');
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
}
