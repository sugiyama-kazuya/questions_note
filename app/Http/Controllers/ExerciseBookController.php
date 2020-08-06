<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateExerciseBook;
use App\ExerciseBook;
use Illuminate\Http\Request;

class ExerciseBookController extends Controller
{
    private $exercise_book;

    public function __construct(ExerciseBook $exerciseBook)
    {
        $this->exercise_book = $exerciseBook;
    }

    /**
     * 問題集一覧のデータを取得
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search_keyword = $request->query('search');
        if ($search_keyword) {
            $exercise_books = $this->exercise_book
                ->fetchExerciseBookCardBaseData()
                ->where("name", "LIKE", "%$search_keyword%")
                ->simplePaginate(30);
        } else {
            $exercise_books = $this->exercise_book->fetchExerciseBookCardBaseData()->simplePaginate(30);
        }

        $exercise_books = $this->exercise_book->addProblemUpdateDate($exercise_books);
        $exercise_books = $this->exercise_book->addProfileUrl($exercise_books);
        $exercise_books = $this->exercise_book->addFavoriteInfo($exercise_books);
        $exercise_books = $this->exercise_book->filteringExerciseBookCard($exercise_books);
        $exercise_books = $this->exercise_book->problemUpdateDateDesc($exercise_books);

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
    public function show(Request $request, $user_id)
    {
        $search_keyword = $request->query('search');
        if ($search_keyword) {
            $exercise_books = $this->exercise_book
                ->fetchExerciseBookCardBaseData()
                ->where('user_id', $user_id)
                ->Where("name", "LIKE", "%$search_keyword%")
                ->simplePaginate(20);
        } else {
            $exercise_books = $this->exercise_book
                ->fetchExerciseBookCardBaseData()
                ->where('user_id', $user_id)
                ->simplePaginate(20);
        }

        $exercise_books = $this->exercise_book->addProblemUpdateDate($exercise_books);
        $exercise_books = $this->exercise_book->addProfileUrl($exercise_books);
        $exercise_books = $this->exercise_book->addFavoriteInfo($exercise_books);
        $exercise_books = $this->exercise_book->filteringExerciseBookCard($exercise_books);
        $exercise_books = $this->exercise_book->problemUpdateDateDesc($exercise_books);

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
