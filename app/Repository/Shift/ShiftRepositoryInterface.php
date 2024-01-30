<?php

namespace App\Repository\Shift;

interface ShiftRepositoryInterface
{
    public function getShiftStart();
    public function start();
    public function end();
    public function getShiftList();
}
