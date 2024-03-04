<?php

namespace Database\Factories;

use App\Constant;
use Carbon\Carbon;
use App\Models\Item;
use App\Models\Order;
use App\Models\Shift;
use App\Models\OrderDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\Factory;

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
    private $date_diff = 399;
    public function definition()
    {
        Shift::truncate();
        Order::truncate();
        OrderDetail::truncate();
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

    public function configure()
    {
        return $this->afterCreating(function (Shift $shift) {
            $orders = Order::factory()->count(rand(10, 30))->create(
                [
                    'shift_id'      => $shift->id,
                    'total_amount'  => 7000,
                    'created_at'    => $shift->created_at,
                    'created_by'    => $shift->created_by,
                    'updated_at'    => $shift->updated_at,
                    'updated_by'    => $shift->updated_by,
                ]
            );
            $orders->each(function (Order $order) {
                $random = rand(1, 5);
                $items  = Item::SELECT('id', 'price')
                          ->where('status', Constant::ENABLE_STATUS)
                          ->whereNull('deleted_at')
                          ->whereNUll('created_by')
                          ->LIMIT($random)
                          ->get();
                $sub_total = 0;
                foreach ($items as $item) {
                    $sub_total =  $sub_total + $item->price;
                    $order_detail = new OrderDetail();
                    $order_detail->quantity       = 1;
                    $order_detail->sub_total      = $item->price;
                    $order_detail->order_id       = $order->id;
                    $order_detail->item_id        = $item->id;
                    $order_detail->discount_price = 0;
                    $order_detail->original_price = $item->price;
                    $order_detail->created_by     = $order->created_by;
                    $order_detail->updated_by     = $order->updated_by;
                    $order_detail->created_at     = $order->created_at;
                    $order_detail->updated_at     = $order->updated_at;
                    $order_detail->save();
                }
                $random_refund = [0,100,200,500,1000,5000];
                $refund = array_rand($random_refund);
                $update_order = Order::find($order->id);
                $update_order -> total_amount = $sub_total;
                $update_order -> payment = $sub_total + $refund;
                $update_order -> refund = $refund;
                $update_order->save();
            });
        });
    }

}
