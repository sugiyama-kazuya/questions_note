<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CategoryCheck;

class CategoryController extends Controller
{
    /**
     * 新規作成
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryCheck $request)
    {
        return $request;
    }
}
