<?php

namespace App\Http\Requests;

use App\Rules\ShiftCheckRule;
use Illuminate\Foundation\Http\FormRequest;

class DiscountDeleteRequest extends FormRequest
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
           'id'   => ['required','numeric',new ShiftCheckRule()],
        ];
    }

    public function messages()
    {
        return [
            'id.required'         => 'Something wrong, try again!',
            'id.numeric'          => 'Something wrong, try again!',
        ];

    }
}
