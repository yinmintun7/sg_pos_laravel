<?php

namespace App\Rules;

use App\Models\Item;
use Illuminate\Contracts\Validation\Rule;

class CashAmountValidate implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $itemIds = request()->input('item');
        foreach ($itemIds as $itemId) {
            $itemPrice = Item::find($itemId)->price;

            if ($itemPrice < $value) {
                return false;
            }
        }
        return true;
    }
    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Amount must not be greater than any of the item prices';
    }
}
