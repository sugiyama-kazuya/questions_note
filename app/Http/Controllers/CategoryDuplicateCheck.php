<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryCheck;

class CategoryDuplicateCheck extends Controller
{
    /**
     * 自身が作成したカテゴリーと重複しているかのチェック
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(CategoryCheck $request)
    {
        return $request;
    }
}
