<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Log;

class FollowersCount extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(User $user, $user_id)
    {
        Log::debug($user_id);
        return response()
            ->json(['follower_count' => $user->currentUser($user_id)->followers_count]);
    }
}
