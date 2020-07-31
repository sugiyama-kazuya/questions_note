<?php

namespace App\Http\Controllers;

use App\ExerciseBook;
use Illuminate\Support\Facades\Log;

class OwnFavoritesOrderByDescController extends Controller
{
    /**
     * 個人がお気に入り登録している問題集を降順で取得
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(ExerciseBook $exercise_book, $user_id)
    {
        $exercise_books = $exercise_book->fetchExerciseBookCardBaseData()->get();
        $exercise_books = $exercise_book->addProblemUpdateDate($exercise_books);
        $exercise_books = $exercise_book->addProfileUrl($exercise_books);
        $exercise_books = $exercise_book->addFavoriteInfo($exercise_books, $user_id);
        $exercise_books = $exercise_book->fetchOwnFavoriteRegisterdExerciseBooks($exercise_books, $user_id);
        $exercise_books = $exercise_book->favoriteCountDesc($exercise_books);

        return response()->json(['exercise_books' => $exercise_books]);
    }
}
