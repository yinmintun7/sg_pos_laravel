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
use App\Repository\Report\ReportRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithStyles;

class OrderMonthlyReport implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    private $all_total_row;
    private $start;
    private $end;
    private $MonthlyReportRepository;
    public function __construct(ReportRepositoryInterface $MonthlyReportRepository)
    {
        DB::connection()->enableQueryLog();
        $this->MonthlyReportRepository = $MonthlyReportRepository;
        $this->all_total_row = 0;
    }
    /**
    * @return \Illuminate\Support\Collection
    */

    public function setRange($start, $end)
    {
        $this->start = $start;
        $this->end   = $end;
        return $this;
    }
    public function collection()
    {
        $result = $this->MonthlyReportRepository->getMonthlySale($this->start, $this->end);
        $array  = [];
        foreach($result['month'] as $key => $month){
            $data = (object)[
                'month'  => $month,
                'amount' => $result['total'][$key],
                'total'  => ''
            ];
            array_push($array,$data);
        }
        // $this->all_total_row = count($array) + 1;
        return new Collection($array);
    }

    public function headings(): array
    {
        return [
            'Month',
            'Amount',
            'Total'
        ];
    }

    public function title(): string
    {
        return 'Monthly Sale Report';
    }

    public function styles($excel)
    {
        return [
            1 => ['font' => ['bold' => true]],
            //  $this->all_total_row => ['font' => ['bold' => true]],
        ];
    }
}
