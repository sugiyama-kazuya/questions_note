<?php

namespace App\Http\Controllers;

use App\Problem;
use App\ExerciseBook;
use App\Http\Requests\CreateProblem;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateProblem;

class ProblemController extends Controller
{
    private $problem;
    private $exercise_book;

    public function __construct(Problem $problem, ExerciseBook $exercise_book)
    {
        $this->problem = $problem;
        $this->exercise_book = $exercise_book;
    }

    /**
     *問題作成画面の表示
     *ログインユーザーが作成している問題集名を全て取得
     *
     * @return \Illuminate\Http\Response
     */
    public function create(ExerciseBook $exercise_book)
    {
        // ログインユーザーの問題集を取得
        $exercise_book_list = $exercise_book->loginUserExerciseBook;

        return response()->json([
            'exercise_book_list' => $exercise_book_list,
        ]);
    }

    /**
     * 問題と解答の作成、問題集に追加または新規作成
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateProblem $request)
    {
        $exercise_book = $this->exercise_book->fetchOrRegister($request);

        $this->problem->newRegister($request, $exercise_book);
    }

    /**
     * 問題画面の表示
     *
     * @param  int  $exercise_books_id
     * @return \Illuminate\Http\Response
     */
    public function show($exercise_books_id)
    {

        $problem_data = Problem::where('exercise_book_id', $exercise_books_id)->get();

        // 取得する問題がない場合
        if ($problem_data->isEmpty()) {
            return response()->json(['exercise_books' => null]);
        } else {
            $user_id = $problem_data->map(function ($data) {
                return $data->user_id;
            });

            $problem_data = $problem_data->map(function ($data) {
                $problem_data = $data;
                Arr::except($problem_data, ['user_id']);
                return $problem_data;
            });

            $problem_count = $problem_data->count('id');

            $problem_data = Arr::add(['problems' => $problem_data], 'count', $problem_count);
            $problem_data['user_id'] = $user_id[0];

            return response()->json(['exercise_books' => $problem_data]);
        }
    }

    /**
     * 問題の更新
     *
     * @param [string] $id
     * @param UpdateProblem $request
     * @return void
     */
    public function update($id, UpdateProblem $request)
    {
        $exercise_book = $this->exercise_book->fetchOrRegister($request);

        $this->problem->problemUpdate($id, $request, $exercise_book);
    }

    /**
     * 問題編集画面の情報
     *
     * @param [type] $id
     * @return void
     */
    public function edit($id)
    {
        $problem = $this->problem->with(['exerciseBook'])->where('id', $id)->first();

        // ログインユーザーの問題集を取得
        $exercise_book_list = $this->exercise_book->loginUserExerciseBook;

        return response()->json([
            'problem' => $problem,
            'exercise_book_list' => $exercise_book_list,
        ]);
    }

    /**
     * 問題の削除
     *
     * @param [string] $problem_id
     * @return void
     */
    public function destroy($problem_id)
    {
        Problem::find($problem_id)->delete();
    }
}
