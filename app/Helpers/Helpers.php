<?php

use App\Helpers\HelperTime;

if(! function_exists('timeToReadbleString')) {
    function timeToReadbleString($time) {
        $convert = new HelperTime();
        return $convert->ReadableDate($time);
    }
}