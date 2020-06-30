<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ExerciseBook;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OwnFavoritesOrderByAsc extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(ExerciseBook $exercise_book, $user_id)
    {
        Log::debug($user_id);
        $exercise_books = $exercise_book->exerciseBookCardRequiredData()->get();

        $exercise_books = $exercise_book->addProfileUrl($exercise_books);
        $exercise_books = $exercise_book->addFavoriteInfo($exercise_books, $user_id);

        // 自分がいいねをしている問題があれば取得、なければ問題集を削除
        $exercise_books = $exercise_books->filter(function ($data) use ($user_id) {
            if ($data->likes()->first()) {
                return (int) $data->likes()->first()->pivot->user_id === (int) $user_id;
            } else {
                return $data = [];
            }
        })->values();

        if (!$exercise_books->isEmpty()) {
            $exercise_books = $exercise_book->filteringRequiredData($exercise_books, $user_id);
        }

        return response()->json(['exercise_books' => $exercise_books]);
    }
}
