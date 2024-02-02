<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CategoryStoreRequest extends FormRequest
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
            'name'       => ['required',
                             Rule::unique('category')->where(function ($query) {
                                 return $query
                                 ->where('name', $this->name)
                                 ->whereNull('deleted_at');
                             }),
                           ],
            'parent_id'  => ['required'],
            'image'      => ['required','mimes:png,jpg,jpeg,gif'],
        ];

    }

    public function messages()
    {
        return [
            'name.required'         => 'Please fill category name!',
            'name.unique'           => 'This category name is already exist!',
            'parent_id.required'    => 'Please select parent_id!',
            'image.required'        => 'Please select photo for category!',
            'image.mimes'           => 'Please upload valid image extension(jpg,jpeg,png,gig)!'
        ];

    }
}
