<?php

namespace App\Policies;

use App\ExerciseBook;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExerciseBookPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can delete the exercise book.
     *
     * @param  \App\User  $user
     * @param  \App\ExerciseBook  $exerciseBook
     * @return mixed
     */
    public function delete(User $user, ExerciseBook $exerciseBook)
    {
        return $user->id == $exerciseBook->user_id;
    }
}
