<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseFormRequest;

class ItemStoreRequest extends FormRequest
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
            'name'         => ['required',
                               Rule::unique('item')->where(function ($query) {
                                   return $query
                                   ->where('name', $this->name)
                                   ->whereNull('deleted_at');
                               }),
                             ],
            'category_id'  => ['required'],
            'price'        => ['required'],
            'quantity'     => ['required'],
            'image'        => ['required','mimes:png,jpg,jpeg,gif'],
        ];
    }

    public function messages()
    {
        return [
            'name.required'             => 'Please fill category name!',
            'name.unique'               => 'This Item name is already exist!',
            'price.required'            => 'Please fill price!',
            'quantity.required'         => 'Please fill quantity!',
            'category_id.required'      => 'Please select category!',
            'image.required'            => 'Please select photo for category!',
            'image.mimes'               => 'Please upload valid image extension(jpg,jpeg,png,gig)!'
        ];

    }
}
