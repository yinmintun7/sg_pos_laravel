<?php

namespace App\Rules;

use App\Models\DiscountItem;
use App\Models\Item;
use App\Models\DiscountPromotion;
use Illuminate\Contracts\Validation\Rule;
use App\Utility;

class DiscountStartExit implements Rule
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
        $conflictingItems = [];
        foreach ($itemIds as $itemId) {
            $item = DiscountItem::find($itemId)->discount_id;
                $discountStart = DiscountPromotion::find($item)->start_date;

                $formattedTime = Utility::changeFormatYmd2mdY($discountStart);

                if ($formattedTime <= $value) {
                    $conflictingItems[] = $item;
                }

        }
        if (!empty($conflictingItems)) {
            return false; // If any item has a discount starting after the specified date, return false
        }
        return true; // If none of the items have a discount starting after the specified date, return true
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The following items already have discounts starting after the specified date.';
    }
}
