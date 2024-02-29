<?php

namespace App\Repository\Report;

use SebastianBergmann\Type\NullType;

interface ReportRepositoryInterface
{
    public function weeklySaleGraph();
    // public function weeklySaleExcel();
    public function monthlySaleGraph();
    public function getDailyReport($start, $end);
}
