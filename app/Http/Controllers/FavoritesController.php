<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExerciseBook;
use Illuminate\Support\Facades\Auth;

class FavoritesController extends Controller
{

    private $exercise_book;

    public function __construct(ExerciseBook $exercise_book)
    {
        $this->exercise_book = $exercise_book;
    }

    /**
     * お気に入りに追加する
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $exercise_book_id = $request->id;
        $login_user_id = Auth::id();
        $this->exercise_book->currentExerciseBook($exercise_book_id)->likes()->detach($login_user_id);
        $this->exercise_book->currentExerciseBook($exercise_book_id)->likes()->attach($login_user_id);
    }
}
