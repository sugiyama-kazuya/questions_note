<?php

namespace App\Http\Controllers;

use App\User;

class OwnFollowersController extends Controller
{
    /**
     * 自身のフォロワーを取得
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke($user_id, User $user)
    {
        $users = $user->find($user_id)->followers;
        $users = $user->addProfileUrl($users);
        return $users;
    }
}
