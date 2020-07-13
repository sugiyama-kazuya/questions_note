<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateExerciseBook;
use App\ExerciseBook;
use Illuminate\Support\Facades\Auth;

class ExerciseBookController extends Controller
{
    private $exercise_book;

    public function __construct(ExerciseBook $exerciseBook)
    {
        $this->exercise_book = $exerciseBook;
    }

    /**
     * 問題一覧のデータを取得
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $exercise_books = $this->exercise_book->fetchExerciseBookCardBaseData()->get();
        $exercise_books = $this->exercise_book->addProfileUrl($exercise_books);

        $exercise_books = $this->exercise_book->addFavoriteInfo($exercise_books);
        $exercise_books = $this->exercise_book->filteringExerciseBookCard($exercise_books);

        return response()->json(['exercise_books' => $exercise_books]);
    }

    /**
     * 問題集の作成
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateExerciseBook $request)
    {
        return $request;
    }

    /**
     * 自身の問題集を取得
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($user_id)
    {
        $exercise_books = $this->exercise_book->fetchExerciseBookCardBaseData()
            ->where('user_id', $user_id)->get();
        $exercise_books = $this->exercise_book->addProfileUrl($exercise_books);
        $exercise_books = $this->exercise_book->addFavoriteInfo($exercise_books);
        $exercise_books = $this->exercise_book->filteringExerciseBookCard($exercise_books);

        return response()->json(['exercise_books' => $exercise_books]);
    }

    /**
     * 問題集の削除
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->exercise_book->find($id)->delete();
    }
}
