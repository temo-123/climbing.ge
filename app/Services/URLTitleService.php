<?php

namespace App\Services;

class URLTitleService
{
    public static function get_url_title($us_title)
    {
        $us_title = mb_strtolower($us_title);

        $us_title = str_replace(" ", "_", $us_title);
        $us_title = str_replace("-", "_", $us_title);
        $us_title = str_replace("(", "", $us_title);
        $us_title = str_replace(")", "", $us_title);
        $us_title = str_replace(". ", "_", $us_title);
        $us_title = str_replace(".", "_", $us_title);
        $us_title = str_replace(",", "", $us_title);
        $us_title = str_replace(":", "", $us_title);
        $us_title = str_replace(";", "", $us_title);
        $us_title = str_replace("{", "", $us_title);
        $us_title = str_replace("}", "", $us_title);
        $us_title = str_replace("[", "", $us_title);
        $us_title = str_replace("]", "", $us_title);
        $us_title = str_replace("'", "", $us_title);
        $us_title = str_replace('"', "", $us_title);

        $us_title = mb_strtolower($us_title);

        return $us_title;
    }
}