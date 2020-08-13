<?php

namespace App\Http\Controllers;

use App\Problem;
use App\ExerciseBook;
use App\Http\Requests\CreateProblem;
use App\Http\Requests\UpdateProblem;
use Illuminate\Support\Facades\DB;

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
    public function create()
    {
        $exercise_books = $this->exercise_book->loginUserExerciseBook;

        return response()->json([
            'exercise_books' => $exercise_books,
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
        DB::transaction(function () use ($request) {
            $exercise_book = $this->exercise_book->fetchOrRegister($request);
            $this->problem->newRegister($request, $exercise_book);
        });
    }

    /**
     * 問題画面の表示
     *
     * @param  [string]  $exercise_books_id
     * @return \Illuminate\Http\Response
     */
    public function show($exercise_books_id)
    {
        $problems = $this->problem->where('exercise_book_id', $exercise_books_id)->get();
        $problems = $problems->map(function ($problem) {
            return $problem->addImageUrl($problem);
        });
        if ($problems->isEmpty()) {
            return response()->json(['exercise_books' => null]);
        } else {
            $problems = $this->problem->addUserIdAndProblemsCount($problems);
            return response()->json(['exercise_books' => $problems]);
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
        DB::transaction(function () use ($id, $request) {
            $exercise_book = $this->exercise_book->fetchOrRegister($request);
            $problem = $this->problem->findOrFail($id);
            $this->authorize('update', $problem);
            $this->problem->problemUpdate($problem, $request, $exercise_book);
        });
    }

    /**
     * 問題編集画面の情報
     *
     * @param [string] $id
     * @return void
     */
    public function edit($id)
    {
        $problem = $this->problem->with(['exerciseBook'])->where('id', $id)->firstOrFail();
        $this->authorize('update', $problem);
        $problem = $this->problem->addImageUrl($problem);
        $exercise_books = $this->exercise_book->loginUserExerciseBook;
        return response()->json([
            'problem' => $problem,
            'exercise_book_list' => $exercise_books,
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
        $problem = $this->problem->findOrFail($problem_id);
        $this->authorize('delete', $problem);
        $problem->delete();
    }
}
