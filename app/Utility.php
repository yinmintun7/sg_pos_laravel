<?php

namespace App;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use App\Models\Shift;
use Carbon\Carbon;

class Utility
{
    public static function saveDebugLog($screen, $querylog)
    {
        $formattedQueries = '';
        foreach ($querylog as $query) {
            $sqlQuery = $query['query'];
            foreach ($query['bindings'] as $binding) {
                $sqlQuery = preg_replace('/\?/', "'" . $binding . "'", $sqlQuery, 1);
            }
            $formattedQueries .= $sqlQuery . PHP_EOL;
        }
        Log::debug($screen . "-\n" . $formattedQueries);
    }

    public static function saveErrorLog($screen, $errorMessage)
    {
        Log::error($screen . "-\n" . $errorMessage);
    }

    public static function getCreateId(array $data)
    {

        $user_id    = Auth::guard('admin')->user()->id;
        $data['created_by'] = $user_id;
        $data['updated_by'] = $user_id;
        return $data;
    }

    public static function getUpdateId(array $data)
    {
        $today_date = date('Y-m-d H:i:s');
        $user_id    = Auth::guard('admin')->user()->id;
        $data['updated_at'] = $today_date;
        $data['updated_by'] = $user_id;
        return $data;
    }

    public static function getDeletedId(array $data)
    {
        $today_date = date('Y-m-d H:i:s');
        $user_id    = Auth::guard('admin')->user()->id;
        $data['deleted_at'] = $today_date;
        $data['deleted_by'] = $user_id;
        return $data;
    }
    public static function getShift()
    {
        $shift_id = Shift::select('id')
                 ->whereNotNull('start_date_time')
                 ->whereNull('end_date_time')
                 ->first();
        return $shift_id;
    }

    public static function cropResize($file, $destination_path, $unique_name)
    {
        if (!is_dir($destination_path)) {
            mkdir($destination_path, 0777, true);
        }
        $modified_image = Image::make($file)
                        ->fit(Constant::IMAGE_WIDTH, Constant::IMAGE_HEIGHT)
                        ->encode();
        $modified_image->save($destination_path . '/' . $unique_name);
    }

    public static function changeFormatmdY2Ymd($dateString)
    {
        $date = Carbon::createFromFormat('m/d/Y', $dateString);
        return $date->format('Y-m-d');
    }
    public static function changeFormatYmd2mdY($dateString)
    {
        $date = Carbon::createFromFormat('Y-m-d', $dateString);
        return $date->format('m/d/Y');
    }

    public static function getLastSevenDay()
    {
        $today_date = '2024-01-24';
        $dates = [Carbon::parse($today_date)->format('Y-m-d')];
        $today = Carbon::parse($today_date);
        for ($i = 0; $i < 6; $i++) {
            $dates[] = $today->subDay()->format('Y-m-d');
        }
        $dates = array_reverse($dates);
        return $dates;
    }


}
