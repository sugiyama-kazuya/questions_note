<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

class OwnFollowersController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(User $user)
    {
        $login_user_id = Auth::id();
        $users = $user->find($login_user_id)->followers;
        return $users;
    }
}
