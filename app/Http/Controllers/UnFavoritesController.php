<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ExerciseBook;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UnFavoritesController extends Controller
{

    private $exercise_book;

    public function __construct(ExerciseBook $exercise_book)
    {
        $this->exercise_book = $exercise_book;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        Log::debug($request);
        $exercise_book_id = $request->id;
        $login_user_id = Auth::id();

        $this->exercise_book->find($exercise_book_id)->likes()->detach($login_user_id);
    }
}
