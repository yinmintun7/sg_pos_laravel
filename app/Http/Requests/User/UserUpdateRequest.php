<?php

namespace App\Http\Requests\User;

use Illuminate\Validation\Rule;
use App\Rules\CheckOldPassword;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'id'               =>['required','numeric'],
            'usertype'         => 'required|in:admin,cashier',
            'password'         => 'required',
            'confirm_password' => 'required|same:password',
            'old_password'     => ['required',new CheckOldPassword()],
            'username' => [
                'required',
                Rule::unique('users')->where(function ($query) {
                    return $query
                        ->where('username', $this->username)
                        ->whereNull('deleted_at');
                })->ignore($this->id),
            ],
        ];

        if ($this->input('usertype') === 'cashier') {
            $rules['username'] = 'required|numeric';
        } elseif ($this->input('usertype') === 'admin') {
            $rules['username'] = 'required|string';
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
            'old_password.required' => 'Please enter old password to change!',
        ];
    }

}
