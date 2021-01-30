<?php

namespace App\Http\Controllers;

use App\Models\User;

class OwnFollowsController extends Controller
{
    /**
     * 自身のフォローを取得
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke($user_id, User $user)
    {
        $users = $user->find($user_id)->followings;
        $users = $user->fetchIsFollowedBy($users, $user_id);
        $users = $user->addProfileUrl($users);
        return $users;
    }
}
