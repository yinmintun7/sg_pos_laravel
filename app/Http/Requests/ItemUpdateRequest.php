<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class ItemUpdateRequest extends FormRequest
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
                               })->ignore($this->id),
                             ],
            'category_id'  => ['required'],
            'price'        => ['required'],
            'quantity'     => ['required'],
            // 'image'        => ['required','mimes:png,jpg,jpeg,gif'],
        ];
        if ($this->has('image')) {
            $rules['image'] = ['required', 'mimes:png,jpg,jpeg,gif'];
        }

        return $rules;
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
