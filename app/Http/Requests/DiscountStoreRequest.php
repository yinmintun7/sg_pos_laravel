<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class DiscountStoreRequest extends FormRequest
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
            'name'          => ['required',
                               Rule::unique('discount_promotion')->where(function ($query) {
                                   return $query
                                   ->where('name', $this->name)
                                   ->whereNull('deleted_at');
                               }),
                               ],
            'amount'       => ['required'],
            'percentage'   => ['required'],
            'start_date'   => ['required'],
            'end_date'     => ['required'],

        ];

    }

    public function messages()
    {
        return [
            'name.required'           => 'Please fill category name!',
            'name.unique'             => 'Discount with this name is already created!',
            'amount.required'         => 'Please fill amount for discount!',
            'amount.required'         => 'Please fill percentage for discount!',
            'start_date.required'     => 'Please select start date for discount !',
            'end_date.required'       => 'Please select start date for discount !',
        ];

    }
}
