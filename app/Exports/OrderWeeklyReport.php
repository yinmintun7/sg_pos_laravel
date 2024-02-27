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
use Maatwebsite\Excel\Concerns\WithStyles;

class OrderWeeklyReport implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    private $all_total_row;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $dates = Utility::getLastSevenDay();
        $result = [];
        $all_total = 0;
        foreach ($dates as $shift_date) {
            $shifts = Shift::whereDate('start_date_time', $shift_date)->get();
            if ($shifts != null) {
                $total_amount = 0;
                foreach ($shifts as $shift) {
                    $sum_shift = Order::where('shift_id', $shift->id)->sum('total_amount');
                    $total_amount = $total_amount + $sum_shift;
                }
                $all_total = $all_total + $total_amount;

                $weekly_date = (object) [
                    'date'   => Carbon::parse($shift_date)->format('Y-m-d'),
                    'amount' => $total_amount + $sum_shift,
                    'total'  => ''
                ];
                array_push($result, $weekly_date);
            }
        }
        $total_row = (object)[
            'date'   => '',
            'amount' => '',
            'total'  => $all_total
        ];
        $this->all_total_row = count($result) + 2;
        array_push($result, $total_row);
        return new Collection($result);
    }

    public function headings(): array
    {
        return [
            'Date',
            'Amount',
            'Total'
        ];
    }

    public function title(): string
    {
        return 'Weekly Sale Report';
    }

    public function styles($excel)
    {
        return [
            1 => ['font' => ['bold' => true]],
            $this->all_total_row => ['font' => ['bold' => true]],
        ];
    }
}
