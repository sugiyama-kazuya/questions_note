<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ExerciseBook;

class CountLikesController extends Controller
{
    /**
     * いいね数を取得
     *
     * @param ExerciseBook $exercise_book
     * @param [type] $id 問題集のID
     * @return Array
     */
    public function __invoke(ExerciseBook $exercise_book, $id)
    {
        $count_likes = $exercise_book->currentExerciseBook($id)->count_likes;
        return ['count' => $count_likes];
    }
}
