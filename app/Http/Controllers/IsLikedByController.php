<?php

namespace App\Http\Controllers;

use App\ExerciseBook;
use Illuminate\Support\Facades\Auth;

class IsLikedByController extends Controller
{
    /**
     * いいねをしているか否か
     *
     * @param ExerciseBook $exercise_book
     * @param [type] $id 問題集のID
     * @return Array
     */
    public function __invoke(ExerciseBook $exercise_book, $id)
    {
        $isLikedBy = $exercise_book->currentExerciseBook($id)->isLikedBy(Auth::user());

        return ['isLike' => $isLikedBy];
    }
}
