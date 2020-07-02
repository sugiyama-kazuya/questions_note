<?php

namespace App\Http\Controllers;

use App\ExerciseBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OwnExerciseBooksController extends Controller
{
    /**
     * ログインユーザーの問題集と問題を取得
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        $login_user = Auth::id();
        $exercise_books = ExerciseBook::with(['problem'])->where('user_id', $login_user)->get();
        return $exercise_books;
    }
}
