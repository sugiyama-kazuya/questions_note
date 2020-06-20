<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Category extends Model
{
    protected $fillable = [
        'name', 'user_id'
    ];

    protected $visible = [
        'id', 'name'
    ];

    /**
     * ログインユーザーのカテゴリーを取得
     *
     * @return Object
     */
    public function getLoginUserCategoryAttribute()
    {
        return $this->where('user_id', Auth::id())->get(['id', 'name']);
    }

    /**
     * カテゴリーの登録
     * 対象のユーザーのカテゴリーデータを取得し、既に登録されているなら取得、されていないなら登録
     *
     * @param [object] $request
     * @return object
     */
    public function fetchOrRegister($request)
    {
        return $this->where('user_id', Auth::id())
            ->firstOrCreate(
                ['name' => $request->category],
                [
                    'name' => $request->category,
                    'user_id' => Auth::id()
                ]
            );
    }
}
