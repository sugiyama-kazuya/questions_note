<?php

namespace App\Http\Controllers;

use App\ExerciseBook;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OwnFavoriteExerciseBooksController extends Controller
{
    public function __invoke(ExerciseBook $exercise_book)
    {
        $login_user_id = Auth::id();

        $exercise_books = $exercise_book->exerciseBookCardRequiredData()->get();

        $exercise_books = $exercise_book->addProfileUrl($exercise_books);
        $exercise_books = $exercise_book->addFavoriteInfo($exercise_books, $login_user_id);

        // 自分がいいねをしている問題があれば取得、なければ問題集を削除
        $exercise_books = $exercise_books->filter(function ($data) use ($login_user_id) {
            if ($data->likes()->first()) {
                return $data->likes()->first()->pivot->user_id === $login_user_id;
            } else {
                return $data = [];
            }
        })->values();

        if (!$exercise_books->isEmpty()) {
            $exercise_books = $exercise_book->filteringRequiredData($exercise_books, $login_user_id);
        }

        return response()->json(['exercise_books' => $exercise_books]);
    }
}
