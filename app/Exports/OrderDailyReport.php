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

class OrderDailyReport implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    private $all_total_row;
    private $start;
    private $end;
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

    public function setRange($start, $end)
    {
        $this->start = $start;
        $this->end   = $end;
        return $this;
    }
    public function collection()
    {
        $result = $this->DailyReportRepository->getDailyReport($this->start, $this->end);
        $this->all_total_row = count($result) + 1;
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
