<?php

namespace App\Http\Controllers;

use App\Models\User;

class FollwingsCountController extends Controller
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
