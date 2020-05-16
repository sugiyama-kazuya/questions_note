<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Problem;
use App\ExerciseBook;
use App\ExerciseBookName;
use App\Http\Requests\CreateProblem;
use App\Http\Requests\CreateNewExerciseBooksName;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProblemController extends Controller
{
    /**
     * 問題一覧画面のデータを取得
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ExerciseBook $exercise_book)
    {
        //        問題カードに必要なデータを取得
        $exercise_books = $exercise_book->with('user:id,name', 'exerciseBooksName:id,name')->get();

        //        必要なデータの絞り込み
        $exercise_books = $exercise_books->map(function ($data) {
            return $data->only(['id', 'updated_at', 'user_id', 'exercise_books_name_id', 'user', 'exerciseBooksName']);
        });

        //        重複している問題は一つに絞る
        $exercise_books = $exercise_books->unique(function ($item) {
            return $item['user_id'] . $item['exercise_books_name_id'];
        });

        return $exercise_books;
    }

    /**
     *問題作成画面の表示
     *
     * @return \Illuminate\Http\Response
     */
    public function create(ExerciseBookName $exercise_book)
    {
        $exercise_books_name_list = $exercise_book->where('user_id', Auth::id())->get(['id', 'name']);

        Log::debug($exercise_books_name_list);

        return $exercise_books_name_list;
    }

    /**
     * 問題と解答の作成
     *
     * @param  \Illuminate\Http\Request  $req
     * @return \Illuminate\Http\Response
     */
    public function store(CreateProblem $req, Problem $problem, ExerciseBook $exercise_book, ExerciseBookName $exercise_book_name)
    {
        $login_user_id = Auth::id();

        //        DB::enableQueryLog();
        //        問題集の名前を登録
        $exercise_book_name->where('user_id', $login_user_id)->firstOrCreate(
            ['name' => $req->exerciseBook],
            ['name' => $req->exerciseBook, 'user_id' => $login_user_id]
        );

        $exercise_book_name_id = $exercise_book_name->where('user_id', $login_user_id)->where('name', $req->exerciseBook)->first('id')->id;

        $exercise_book->fill([
            'exercise_books_name_id' => $exercise_book_name_id,
            'user_id' => $login_user_id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ])->save();

        // 先ほど登録した問題集のIDを取得
        $insert_exercise_book_id = $exercise_book->where('user_id', $login_user_id)->where('exercise_books_name_id', $exercise_book_name_id)->first('id')->id;

        Log::debug($insert_exercise_book_id);

        // 問題と答えを登録
        $problem->fill([
            'content' => $req->problem,
            'answer' => $req->answer,
            'user_id' => $login_user_id,
            'exercise_book_id' => $insert_exercise_book_id
        ])->save();
    }

    /**
     * 問題画面の表示
     *
     * @param  int  $exercise_books_id
     * @return \Illuminate\Http\Response
     */
    public function show($exercise_books_id)
    {
        $problem_data = Problem::where('user_id', Auth::id())->where('exercise_book_id', $exercise_books_id)->get();

        $problem_count = $problem_data->count('id');

        $problem_data = Arr::add(['problem' => $problem_data], 'count', $problem_count);

        return response()->json(['problem_data' => $problem_data]);
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

    public function createExerciseBooksName(CreateNewExerciseBooksName $req)
    {
        return $req;
    }
}
