<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class SettingStoreRequest extends FormRequest
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
            'company_name' => [
            'required',
             Rule::unique('setting')->where(function ($query) {
                 return $query
                        ->where('company_name', $this->company_name)
                        ->whereNull('deleted_at');
             }),

            ],
            'company_phone' => [
             'required'
            ],
            'company_email' => [
             'required',
             'email',
             Rule::unique('setting')->where(function ($query) {
                 return $query
                        ->where('company_email', $this->company_email)
                        ->whereNull('deleted_at');
             }),
            ],
            'company_address' => [
             'required'
            ],
            'company_logo'      => ['required','mimes:png,jpg,jpeg,gif'],
         ];
    }
    public function messages()
    {
        return [
            'company_name.required'     => 'Please Fill Company Name',
            'company_name.unique'       => 'Company name is already taken',
            'company_name.required'     => 'Please Fill Company Name',
            'company_phone.required'    => 'Please Fill Company Phone',
            'company_email.required'    => 'Please Fill Company Email',
            'company_email.email'       => 'Email format is wrong',
            'company_email.unique'      => 'Email is already taken',
            'company_address.required'  => 'Please Fill Company Address',
            'company_logo.required'     => 'Please upload logo for company!',
            'company_logo.mimes'        => 'Please upload valid image extension(jpg,jpeg,png,gig)!'
        ];
    }

}
