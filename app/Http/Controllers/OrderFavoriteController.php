<?php

namespace App\Http\Controllers;

use App\ExerciseBook;

class OrderFavoriteController extends Controller
{
    public function __invoke(ExerciseBook $exercise_book)
    {
        $exercise_books = $exercise_book->getExerciseBookData()->get();
        $exercise_books = $exercise_book->addProfileUrl($exercise_books);
        $exercise_books = $exercise_book->filteringRequiredData($exercise_books);
        $exercise_books = $exercise_books->sortByDesc(function ($data) {
            return $data['favolite_count'];
        })->values();

        return response()->json(['exercise_books' => $exercise_books]);
    }
}
