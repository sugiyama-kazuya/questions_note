<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class unFollow extends Controller
{
    /**
     * フォローを外す
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, $user_id)
    {
        $login_user = Auth::user();

        if ($login_user === null) {
            return;
        }

        // 自分自身のフォロー外そうとした場合（自分自身はフォローできない）
        if ($login_user->id === (int) $user_id) {
            return abort('404', 'Cannot follow yourself');
        }

        $current_user = $this->user->currentUser($login_user->id);
        $current_user->followings()->detach($user_id);

        return response()->json(['is_follwed_by' => $current_user->isFollowedBy($user_id)]);
    }
}
