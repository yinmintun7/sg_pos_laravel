<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Shift;

class ShiftCheckRule implements Rule
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
        $shift_check_rule = Shift::whereNotNull('start_date_time')
                            ->whereNull('end_date_time')
                            ->count();
        return $shift_check_rule === 0;
    }
    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Cannot delete while shift is opening';
    }
}
