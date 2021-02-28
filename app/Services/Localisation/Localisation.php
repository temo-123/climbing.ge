<?php
namespace App\Services\localisation;

class Localisation
{
    public function locale()
    {
        $locale = request()->segment(1, '');

        if($locale && in_array($locale, config("app.locales" ))){
            return $locale;
        }
        return "";
    }
}