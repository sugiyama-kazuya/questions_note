<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function follow(Request $request)
    {
        $login_user = Auth::user();

        if ($login_user === null) {
            return;
        }

        // 自分自身にをフォローを使用とした時（自分自身はフォローできない）
        if ($login_user->id === (int) $request->user_id) {
            return abort('404', 'Cannot follow yourself');
        }

        $current_user = $this->user->currentUser($login_user->id);
        $current_user->followings()->dettach($request->user_id);
        $current_user->followings()->attach($request->user_id);

        return response()->json(['is_follwed_by' => $current_user->isFollowedBy($request->user_id)]);
    }

    public function unfollow(Request $request)
    {
        $login_user = Auth::user();

        if ($login_user === null) {
            return;
        }

        // 自分自身のフォロー外そうとした場合（自分自身はフォローできない）
        if ($login_user->id === (int) $request->user_id) {
            return abort('404', 'Cannot follow yourself');
        }

        $current_user = $this->user->currentUser($login_user->id);
        $current_user->followings()->dettach($request->user_id);

        return response()->json(['is_follwed_by' => $current_user->isFollowedBy($request->user_id)]);
    }
}
