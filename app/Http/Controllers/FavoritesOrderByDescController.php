<?php

namespace App\Http\Controllers;

use App\ExerciseBook;

class FavoritesOrderByDescController extends Controller
{
    /**
     * 全体のお気に入り数の降順で問題集を取得
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(ExerciseBook $exercise_book)
    {
        $exercise_books = $exercise_book->fetchExerciseBookCardBaseData()->get();
        $exercise_books = $exercise_book->addProblemUpdateDate($exercise_books);
        $exercise_books = $exercise_book->addProfileUrl($exercise_books);
        $exercise_books = $exercise_book->addFavoriteInfo($exercise_books);
        $exercise_books = $exercise_book->filteringExerciseBookCard($exercise_books);
        $exercise_books = $exercise_book->favoriteCountDesc($exercise_books);

        return response()->json(['exercise_books' => $exercise_books]);
    }
}
