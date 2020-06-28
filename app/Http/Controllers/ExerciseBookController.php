<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
     * 自身の問題集一覧
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $exercise_books = $this->exercise_book->with(['user', 'problem'])->where('user_id', Auth::id())->get();

        return response()->json(['exercise_books' => $exercise_books]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $exercise_books = $this->exercise_book->exerciseBookCardRequiredData()
            ->where('user_id', $user_id)->get();
        $exercise_books = $this->exercise_book->addProfileUrl($exercise_books);
        $exercise_books = $this->exercise_book->addFavoriteInfo($exercise_books, $user_id);
        $exercise_books = $this->exercise_book->filteringRequiredData($exercise_books, $user_id);

        return response()->json(['exercise_books' => $exercise_books]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
