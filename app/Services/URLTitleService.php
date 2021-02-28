<?php

namespace App\Services;

class URLTitleService
{
    public static function get_url_title($us_title)
    {
        $us_title_arr = explode( ' ', $us_title);
        return implode("_", $us_title_arr);
    }
}