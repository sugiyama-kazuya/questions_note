<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProblem extends FormRequest
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
            'problem' => 'required|max:200',
            'answer' => 'required|max:200',
            'url' => 'nullable|url',
            'exerciseBook' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'exerciseBook.required' => '問題集を選択してください。'
        ];
    }
}
