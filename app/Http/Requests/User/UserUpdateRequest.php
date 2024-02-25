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
        $user_id = $this->input('id');
        $rules = [
            'id'               => ['required', 'numeric'],
            'usertype'         => ['required', 'in:admin,cashier'],
            'old_password'     => ['required', new CheckOldPassword($user_id)],
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

        if ($this->filled('password')) {
            $rules['password'] = 'required|min:6';
            $rules['confirm_password'] = 'required|same:password';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'username.required'         => 'Please fill username',
            'username.string'           => 'Username must be a string',
            'username.numeric'          => 'Username must be numeric',
            'username.unique'           => 'Username is already taken',
            'usertype.required'         => 'Please fill usertype',
            'old_password.required'     => 'Please enter old password to change!',
            'password.required'         => 'Please fill password',
            'password.min'              => 'Password must be at least 6 characters long',
            'confirm_password.required' => 'Please confirm password',
            'confirm_password.same'     => 'Password and confirm password must match',
        ];
    }

}
