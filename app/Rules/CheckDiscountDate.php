<?php

namespace App\Rules;

use App\Models\DiscountItem;
use App\Models\Item;
use App\Models\DiscountPromotion;
use Illuminate\Contracts\Validation\Rule;
use App\Utility;

class CheckDiscountDate implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    private $discount_item;
    public function __construct()
    {
        $this->discount_item = [];
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
        $parameter_date = Utility::changeFormatmdY2Ymd($value);
        $this->discount_item = [];
        $valid = true;

        if ($itemIds !== null) {

            foreach ($itemIds as $item) {
                $discount_exit = DiscountItem::selectRaw('count(T02.id) as total')
                    ->leftJoin('discount_promotion', 'discount_promotion.id', 'discount_item.discount_id')
                    ->where('discount_item.discount_id', $item)
                    ->whereDate('discount_promotion.start_date', '<=', $parameter_date)
                    ->whereDate('discount_promotion.end_date', '>=', $parameter_date)
                    ->where('discount_promotion.id', '!=', request()->input('id'))
                    ->count();
                if ($discount_exit > 0) {
                    $item_name = Item::find($item)->name;
                    $this->discount_item[] = $item_name;
                    $valid = false;
                }
            }

        }

        return $valid;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return implode(',', $this->discount_item) . ' already has a discount promotion.';
    }

}
