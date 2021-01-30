<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExerciseBook;

class FavoritesCountController extends Controller
{
    /**
     * 特定の問題集のお気に入り数の取得
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(ExerciseBook $exercise_book, $id)
    {
        $favorites_count = $exercise_book->currentExerciseBook($id)->count_likes;
        return ['count' => $favorites_count];
    }
}
