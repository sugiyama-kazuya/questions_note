<?php

namespace App\Http\Controllers;

use App\Models\ExerciseBook;
use Illuminate\Http\Request;

class FavoritesOrderByDescController extends Controller
{
    /**
     * 全体のお気に入り数の降順で問題集を取得
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, ExerciseBook $exercise_book)
    {
        $search_keyword = $request->query('search');
        if ($search_keyword) {
            $exercise_books = $exercise_book->BaseData()
                ->where("name", "LIKE", "%$search_keyword%")
                ->simplePaginate(30);
        } else {
            $exercise_books = $exercise_book->BaseData()->simplePaginate(30);
        }

        $exercise_books = $exercise_book->addProblemUpdateDate($exercise_books);
        $exercise_books = $exercise_book->addProfileUrl($exercise_books);
        $exercise_books = $exercise_book->addFavoriteInfo($exercise_books);
        $exercise_books = $exercise_book->filteringCardData($exercise_books);
        $exercise_books = $exercise_book->favoriteCountDesc($exercise_books);

        return response()->json(['exercise_books' => $exercise_books]);
    }
}
