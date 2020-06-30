<?php

namespace App\Http\Controllers;

use App\User;

class FollwingsCount extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(User $user, $user_id)
    {
        return response()
            ->json(['followings_count' => $user->currentUser($user_id)->followings_count]);
    }
}
