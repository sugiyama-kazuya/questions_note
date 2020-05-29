<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * プロフィール画面の情報の取得
     *
     * @param User $user
     * @param [string] $user_id
     * @return object
     */
    public function show(User $user, $user_id): object
    {
        $login_user_id = Auth::id();
        $user_id = (int) $user_id;

        // ユーザーがログインしていない場合
        if ($login_user_id === null) {
            $current_user = $user->getProfileRequiredData($user_id);
            return response()->json(['user' => $current_user]);
        }

        // ログインユーザー自身のページの場合、それ以外のページの場合
        if ($login_user_id === (int) $user_id) {
            $current_user = $user->getProfileRequiredData($user_id);
        } else {
            $current_user = $user->getProfileRequiredData($user_id);
            $current_user['is_followed_by'] = $current_user->isFollowedBy($login_user_id);
        }
        return response()->json(['user' => $current_user]);
    }

    public function edit()
    {
    }

    public function update()
    {
    }
}
