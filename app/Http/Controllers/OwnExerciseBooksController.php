<?php

namespace App\Http\Controllers;

use App\ExerciseBook;
use Illuminate\Support\Facades\Auth;

class OwnExerciseBooksController extends Controller
{
    /**
     * ログインユーザーの問題集と問題を取得
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(ExerciseBook $exercise_book)
    {
        $login_user = Auth::id();
        $exercise_books = $exercise_book->with(['problem'])->where('user_id', $login_user)->get();
        $exercise_books = $exercise_book->addProblemUpdateDate($exercise_books);
        $exercise_books = $exercise_book->problemUpdateDateDesc($exercise_books);
        return $exercise_books;
    }
}
