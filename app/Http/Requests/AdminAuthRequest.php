<?php

namespace App\Http\Requests;

use App\Constant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminAuthRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'username' => ['required'],
            'password' => ['required', 'min:6'],
        ];
    }

    public function messages()
    {
        return [
            'username.required'  => 'Username must not be empty',
            'password.min'       => 'Password must be at least 6 characters long',
            'password.required'  => 'Password must not be empty',

        ];
    }
}
