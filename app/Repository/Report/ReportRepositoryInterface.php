<?php

namespace App\Repository\Report;

interface ReportRepositoryInterface
{
    public function weeklySaleGraph();
    // public function weeklySaleExcel();
    public function monthlySaleGraph();
    public function getDailyReport();
}
