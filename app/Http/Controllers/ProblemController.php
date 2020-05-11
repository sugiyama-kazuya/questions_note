<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Problem;
use App\ExerciseBook;
use App\Http\Requests\CreateExerciseBook;
use App\ExerciseBookName;
use App\Http\Requests\CreateProblem;
use App\Http\Requests\CreateAnswer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ProblemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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
            ['name' => $req->exerciseBook, 'user_id'=> $login_user_id]
        );
//        Log::debug(DB::getQueryLog());

//        問題と答えを登録
        $problem->fill([
            'content' => $req->problem,
            'answer' => $req->answer,
            'user_id' => $login_user_id
        ])->save();

        $insert_problem_id = $problem->where('user_id', $login_user_id)->where('content', $req->problem)->first('id')->id;
        $exercise_book_name_id = $exercise_book_name->where('user_id',$login_user_id)->where('name', $req->exerciseBook)->first('id')->id;

        $exercise_book->fill([
            'exercise_books_name_id' => $exercise_book_name_id,
            'problem_id' => $insert_problem_id,
            'user_id' => $login_user_id
        ])->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
