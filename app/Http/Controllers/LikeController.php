<?php

namespace App\Http\Controllers;

use App\ExerciseBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LikeController extends Controller
{
    private $exercise_book;

    public function __construct(ExerciseBook $exercise_book)
    {
        $this->exercise_book = $exercise_book;
    }

    /**
     * いいねを追加する
     *
     * @param Request $request
     * @return void
     */
    public function like(Request $request)
    {
        $exercise_book_id = $request->id;
        $login_user_id = Auth::id();

        $this->exercise_book->currentExerciseBook($exercise_book_id)->likes()->detach($login_user_id);
        $this->exercise_book->currentExerciseBook($exercise_book_id)->likes()->attach($login_user_id);
    }

    /**
     * いいねを削除する
     *
     * @param Request $request
     * @return void
     */
    public function unlike(Request $request)
    {
        $exercise_book_id = $request->id;
        $login_user_id = Auth::id();

        $this->exercise_book->currentExerciseBook($exercise_book_id)->likes()->detach($login_user_id);
    }
}
