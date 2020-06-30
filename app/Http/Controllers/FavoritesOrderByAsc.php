<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ExerciseBook;

class FavoritesOrderByAsc extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(ExerciseBook $exercise_book)
    {
        $exercise_books = $exercise_book->exerciseBookCardRequiredData()->get();
        $exercise_books = $exercise_book->addProfileUrl($exercise_books);
        $exercise_books = $exercise_book->addFavoriteInfo($exercise_books);

        $exercise_books = $exercise_book->filteringRequiredData($exercise_books);
        $exercise_books = $exercise_books->sortByDesc(function ($data) {
            return $data['favorite_count'];
        })->values();


        return response()->json(['exercise_books' => $exercise_books]);
    }
}
