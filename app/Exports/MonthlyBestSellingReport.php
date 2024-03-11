<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\Shift;
use App\Repository\Report\ReportRepositoryInterface;
use App\Utility;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;

class MonthlyBestSellingReport implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    private $all_total_row;
    private $month;
    private $DailyReportRepository;
    public function __construct(ReportRepositoryInterface $DailyReportRepository)
    {
        DB::connection()->enableQueryLog();
        $this->DailyReportRepository = $DailyReportRepository;
        $this->all_total_row = 0;
    }
    /**
    * @return \Illuminate\Support\Collection
    */

    public function setRange($month)
    {
        $this->month = $month;
        return $this;
    }
    public function collection()
    {
        $array = [];
        $all_total = 0;
        $result = $this->DailyReportRepository->monthlyBestSellingList($this->month);
        foreach ($result as $data) {
            $all_total += $data->total_sub_total;
            $data = (object) [
                'name' => $data->name,
                'total_quantity' => intval($data->total_quantity),
                'total_sub_total' => intval($data->total_sub_total),
                'total'    => '',
            ];
            array_push($array, $data);
        }
        $total_row = (object) [
            'name' => '',
            'total_quantity' => '',
            'total_sub_total' => '',
            'total' => $all_total,
        ];
        array_push($array, $total_row);
        $this->all_total_row = count($result) + 2;
        return new Collection($array);
    }

    public function headings(): array
    {
        return [
            'ItemName',
            'Quantity',
            'SubTotal',
            'Total'
        ];
    }
    public function title(): string
    {
        return 'Weekly BestSelling Report';
    }

    public function styles($excel)
    {
        return [
            1 => ['font' => ['bold' => true]],
            $this->all_total_row => ['font' => ['bold' => true]],
        ];
    }
}
