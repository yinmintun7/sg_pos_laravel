<?php

namespace App\Repository\Report;

use SebastianBergmann\Type\NullType;

interface ReportRepositoryInterface
{
    public function weeklySaleGraph();
    public function getMonthlySale($start_month, $end_month);
    public function getDailyReport($start, $end);
    public function dailyBestSellingList();
    public function monthlyBestSellingList($month);
    public function paymentHistory($date);
}
