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
        $exercise_books = $exercise_book->with('user', 'exerciseBooksName', 'likes')->get();

        $exercise_books = $exercise_book->filteringRequiredData($exercise_books, $likes = true);

        $exercise_books = $exercise_books->map(function ($data) {
            $exercise_books_data = $data;
            $exercise_books_data['count'] = $data['likes']->count();
            unset($exercise_books_data['likes']);
            return $exercise_books_data;
        });

        $exercise_books = $exercise_books->sortByDesc(function ($data) {
            return $data['count'];
        })->values();

        return response()->json(['exerciseBooks' => $exercise_books]);
    }
}
