<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class CategoryCheck extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     *
     *自身が作成したカテゴリーと重複しているかどうか
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', Rule::unique(
                'categories',
                'name'
            )->where(function ($query) {
                return $query->where('user_id', Auth::id());
            })]
        ];
    }
}
