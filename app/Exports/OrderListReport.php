<?php

namespace App\Exports;

use App\Utility;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Shift;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Repository\Order\OrderRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithStyles;

class OrderListReport implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    private $all_total_row;
    private $shift_id;
    private $OrderRepository;
    public function __construct(OrderRepositoryInterface $OrderRepository)
    {
        DB::connection()->enableQueryLog();
        $this->OrderRepository = $OrderRepository;
        $this->all_total_row = 0;
    }
    /**
    * @return \Illuminate\Support\Collection
    */

    public function setShiftId($shift_id)
    {
        $this->shift_id = $shift_id;
        return $this;
    }
    public function collection()
    {
        $result    = $this->OrderRepository->getOrderList($this->shift_id);
        $array     = [];
        $all_total = 0;
        foreach ($result as $order) {
            $all_total = $all_total + $order->total_amount;
            $data = (object)[
                'order_no' => $order->order_no,
                'date'     => $order->created_at,
                'amount'   => $order->total_amount,
                'total'    => '',
            ];
            array_push($array, $data);
        }
        $total_row = (object)[
            'order_no' => '',
            'date'     => '',
            'amount'   => '',
            'total'    =>  $all_total,
        ];
        array_push($array, $total_row);
        $this->all_total_row = count($array) + 1;
        return new Collection($array);
    }

    public function headings(): array
    {
        return [
            'Order_no',
            'Date',
            'Amount',
            'Total'
        ];
    }

    public function title(): string
    {
        return 'Order List Report';
    }

    public function styles($excel)
    {
        return [
            1 => ['font' => ['bold' => true]],
            $this->all_total_row => ['font' => ['bold' => true]],
        ];
    }
}
