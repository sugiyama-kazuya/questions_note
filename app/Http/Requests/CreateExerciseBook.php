<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\ExerciseBook;

class CreateExerciseBook extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:App\ExerciseBook,name',
            'problem_id' => 'required',
            'user_id' => 'required'
        ];
    }

        /**
     * エラーメッセージ
     *
    //  * @return void
    //  */
    // public function messages()
    // {
    //     return [
    //         'name.required' => '問題は入力必須です',
    //         'name.'
    //         'user_id.required' => 'ユーザーIDが空です'
    //     ];
    // }

}
