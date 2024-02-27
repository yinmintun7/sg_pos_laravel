<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderStatusRequest extends FormRequest
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
           'corderId'  => ['required','numeric'],
           'status'   => ['required','numeric']
        ];
    }
    public function messages()
    {
        return [
            'orderId.required'  => 'OrderId rquired too cancel order!',
            'status.required'   => 'Order status rquired too cancel order!',
            'orderId.numeric'   => 'Subtotal must be numeric!',
            'status.numeric'    => 'Shift_id must be numeric!',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['errors' => $validator->errors()], 422));
    }
}
