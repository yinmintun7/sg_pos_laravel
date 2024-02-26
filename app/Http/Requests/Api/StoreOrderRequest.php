<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ShiftCheckRule;
use App\Models\Shift;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreOrderRequest extends FormRequest
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
            'items'    => ['required', 'array'],
            'subTotal' => ['required', 'numeric'],
            'shift_id' => [
                'required',
                'numeric',
                // function ($attribute, $value, $fail) {
                //     if (!Shift::where('id', $value)
                //         ->whereNotNull('start_date_time')
                //         ->whereNull('end_date_time')
                //         ->exists()) {
                //         $fail('Cannot order while shift is closing.');
                //     }
                // }
            ],
        ];
    }

    public function messages()
    {
        return [
            'items.required'    => 'Need at least one item to make an order!',
            'subTotal.required' => 'To make an order, subtotal is required!',
            'subTotal.numeric'  => 'Subtotal must be numeric!',
            'shift_id.required' => 'To make an order, shift_id is required!',
            'shift_id.numeric'  => 'Shift_id must be numeric!',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['errors' => $validator->errors()], 422));
    }
}
