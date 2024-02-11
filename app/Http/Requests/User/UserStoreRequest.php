<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserStoreRequest extends FormRequest
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
        $rules = [
            'usertype' => 'required',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
            'username' => [
                'required',
                Rule::unique('users')->where(function ($query) {
                    return $query
                        ->where('username', $this->username)
                        ->whereNull('deleted_at');
                }),
            ],
        ];

        if ($this->input('usertype') === 'cashier') {
            $rules['username'] = 'required|numeric'; // Allow only numeric input for cashier
        }
         elseif ($this->input('usertype') === 'admin') {
            $rules['username'] = 'required|string'; // Allow only string input for admin
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'username.required'     => 'Please fill username',
            'username.string'       => 'Username must be a string',
            'username.numeric'      => 'Username must be numeric',
            'username.unique'       => 'Username is already taken',
            'usertype.required'     => 'Please fill usertype',
            'password.required'     => 'Please fill password',
            'confirm_password.required' => 'Please confirm password',
            'confirm_password.same' => 'Password and confirm password must match',
        ];
    }

}
