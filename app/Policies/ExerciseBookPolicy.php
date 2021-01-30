<?php

namespace App\Policies;

use App\Models\ExerciseBook;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExerciseBookPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can delete the exercise book.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\ExerciseBook
     * @return mixed
     */
    public function delete(User $user, ExerciseBook $exerciseBook)
    {
        return $user->id == $exerciseBook->user_id;
    }
}
