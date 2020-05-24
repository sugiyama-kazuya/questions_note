<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ExerciseBook;
use Illuminate\Support\Facades\Log;

class GetOwnProblemExercizeBooksController extends Controller
{
    /**
     * 自身の問題集を取得
     *
     * @param ExerciseBook $exercise_book
     * @param [type] $user_id
     * @return Array
     */
    public function __invoke(ExerciseBook $exercise_book, $user_id)
    {
        $exercise_books = $exercise_book->with('user', 'exerciseBooksName')->where('user_id', $user_id)->get();

        $exercise_books = $exercise_book->filteringRequiredData($exercise_books);

        return response()->json(['exerciseBooks' => $exercise_books]);
    }
}
