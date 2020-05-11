<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class CreateProblem extends FormRequest
{

    public function loginUserId()
    {
        return Auth::id();
    }
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
            'problem' => 'required|max:255',
            'answer' => 'required|max:255',
            'category' => 'required',
            'exerciseBook' => 'required'
        ];
    }

}
