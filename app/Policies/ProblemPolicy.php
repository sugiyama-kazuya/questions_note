<?php

namespace App\Policies;

use App\Problem;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProblemPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the problem.
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
     * Determine whether the user can delete the problem.
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
