<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class FollowController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke($user_id, User $user)
    {
        $login_user = Auth::user();

        if ($login_user === null) {
            return;
        }

        // 自分自身にをフォローを使用とした時（自分自身はフォローできない）
        if ($login_user->id === (int) $user_id) {
            return abort('404', 'Cannot follow yourself');
        }

        $current_user = $user->currentUser($login_user->id);
        $current_user->followings()->detach($user_id);
        $current_user->followings()->attach($user_id);

        return response()->json(['is_follwed_by' => $current_user->isFollowedBy($user_id)]);
    }
}
