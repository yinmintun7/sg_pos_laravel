<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CashAmountValidate;
use App\Rules\DiscountEndExit;
use App\Rules\DiscountStartExit;

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
        $rules = [
            'name'           => ['required',
                                  Rule::unique('discount_promotion')->where(function ($query) {
                                      return $query
                                          ->where('name', $this->name)
                                          ->whereNull('deleted_at');
                                  }),
            ],
            'discount_type'  => ['required'],
            'amount'         => ['required'],
            'start_date'     => ['required', 'date'],
            'end_date'       => ['required', 'date', 'after:start_date'],
            'description'    => ['required'],
            'item'           => ['required', 'array']
        ];

        if ($this->input('discount_type') == 'percentage') {
            $rules['amount'] = ['required','numeric', 'max:100'];
        }
        if ($this->filled('item')) {
            if ($this->input('discount_type') == 'cash') {
                $rules['amount'] = ['required', 'numeric'];
            }
        }
        return $rules;
    }


    public function messages()
    {
        return [
            'name.required'           => 'Please fill category name!',
            'name.unique'             => 'Discount with this name is already created!',
            'amount.required'         => 'Please fill amount for discount!',
            'discount_type.required'  => 'Please select discount type
            !',
            'start_date.required'     => 'Please select start date for discount!',
            'end_date.required'       => 'Please select end date for discount!',
            'end_date.after'          => 'End date must be after the start date!',
            'item.required'           => 'Please choose item at least one!',
            'item.array'              => 'Please choose item at least one!',
            'description.required'    => 'Please write description for this promotion!',
            'amount.max'              => 'The amount must be less than or equal to 100!',
        ];

    }
}
