<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateExerciseBook;

class CreateExerciseBookCheck extends Controller
{
    /**
     * 問題集の作成時のバリデーションチェック
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(CreateExerciseBook $request)
    {
        return $request;
    }
}
