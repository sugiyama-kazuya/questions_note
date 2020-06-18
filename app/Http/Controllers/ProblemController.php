<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Problem;
use App\ExerciseBook;
use App\Category;
use App\Http\Requests\CreateProblem;
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
        $login_user_id = Auth::id();

        $exercise_books = $exercise_book->exerciseBookCardRequiredData()->get();
        $exercise_books = $exercise_book->addProfileUrl($exercise_books);

        if ($login_user_id) {
            $exercise_books = $exercise_book->addFavoriteInfo($exercise_books, $login_user_id);
            $exercise_books = $exercise_book->filteringRequiredData($exercise_books, $login_user_id);
        } else {
            $exercise_books = $exercise_book->addFavoriteInfo($exercise_books);
            $exercise_books = $exercise_book->filteringRequiredData($exercise_books);
        }

        return response()->json(['exercise_books' => $exercise_books]);
    }

    /**
     *問題作成画面の表示
     *ログインユーザーが作成している問題集名を全て取得
     *
     * @return \Illuminate\Http\Response
     */
    public function create(ExerciseBook $exercise_book, Category $category)
    {
        // ログインユーザーの問題集を取得
        $exercise_book_list = $exercise_book->loginUserExerciseBook;

        // ログインユーザーのカテゴリーを取得
        $category_list = $category->loginUserCategory;

        return response()->json([
            'exercise_book_list' => $exercise_book_list,
            'category_list' => $category_list
        ]);
    }

    /**
     * 問題と解答の作成、問題集に追加または新規作成
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateProblem $request, Problem $problem, ExerciseBook $exercise_book, Category $category)
    {
        //ログインユーザーのカテゴリーを取得し、
        //リクエストから送られてきたカテゴリーがあれば取得、なければ登録
        $category = $category->fetchOrRegister($request);

        //ログインユーザーの問題集を取得し、
        //リクエストから送られてきた問題集があれば取得、なければ登録
        $exercise_book = $exercise_book->fetchOrRegister($request, $category->id);

        // 問題と答えの登録
        $problem->fill([
            'content' => $request->problem,
            'answer' => $request->answer,
            'url' => $request->url,
            'user_id' => Auth::id(),
            'exercise_book_id' => $exercise_book->id,
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
