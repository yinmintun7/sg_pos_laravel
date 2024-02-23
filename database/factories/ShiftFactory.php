<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Shift;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shift>
 */
class ShiftFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Shift::class;
    private $date_diff = 39;
    public function definition()
    {
        $today_start = Carbon::today();
        $start_time = $today_start->subDay($this->date_diff)->addHour(rand(6, 18))->addMinute(rand(0, 59));
        $end_time = $start_time->copy()->addHour();
        $this->date_diff--;
        return [
        'start_date_time' => $start_time,
        'end_date_time'   => $end_time,
        'status'          => 0,
        'created_at'      => $start_time,
        'created_by'      => 1,
        'updated_at'      => $start_time,
        'updated_by'      => 1,
        ];
    }
}
