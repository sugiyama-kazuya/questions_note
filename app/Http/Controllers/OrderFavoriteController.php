<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ExerciseBook;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;

class OrderFavoriteController extends Controller
{
    public function __invoke(ExerciseBook $exercise_book)
    {
        $exercise_books = $exercise_book->getExerciseBookDataFormat()->get();

        $exercise_books = $exercise_book->filteringRequiredData($exercise_books);

        $exercise_books = $exercise_books->sortByDesc(function ($data) {
            return $data['favolite_count'];
        })->values();

        return response()->json(['exercise_books' => $exercise_books]);
    }
}
