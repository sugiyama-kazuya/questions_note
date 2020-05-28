<?php

namespace App\Http\Controllers;

use App\ExerciseBook;
use Illuminate\Support\Facades\Auth;

class OwnFavoriteExerciseBooksController extends Controller
{
    public function __invoke(ExerciseBook $exercise_book)
    {
        $login_user_id = Auth::id();

        $exercise_books = $exercise_book->getExerciseBookDataFormat()->get();

        // 自分がいいねをしている問題だけに絞り込み
        $exercise_books = $exercise_books->filter(function ($data) use ($login_user_id) {
            return $data->likes()->first()->pivot->user_id === $login_user_id;
        })->values();

        $exercise_books = $exercise_book->filteringRequiredData($exercise_books, $login_user_id);

        return response()->json(['exercise_books' => $exercise_books]);
    }
}
