<?php

namespace App\Helpers;

class FormatHelper
{
    public static function shortRupiah($number)
    {
        if ($number >= 1000000000) {
            return number_format($number / 1000000000,) . ' M';
        } elseif ($number >= 1000000) {
            return number_format($number / 1000000,) . ' Jt';
        } elseif ($number >= 1000) {
            return number_format($number / 1000) . ' Rb';
        }

        return number_format($number);
    }
}
