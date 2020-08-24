<?php

namespace App\Policies;

use App\Problem;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProblemPolicy
{
    use HandlesAuthorization;

    /**
     * 問題編集者と問題作成者idをチェック
     *
     * @param  \App\User  $user
     * @param  \App\Problem  $problem
     * @return mixed
     */
    public function update(User $user, Problem $problem)
    {
        return $user->id == $problem->user_id;
    }

    /**
     * 問題削除者と問題作成者idをチェック
     *
     * @param  \App\User  $user
     * @param  \App\Problem  $problem
     * @return mixed
     */
    public function delete(User $user, Problem $problem)
    {
        return $user->id == $problem->user_id;
    }
}
