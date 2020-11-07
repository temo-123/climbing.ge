<?php 

namespace App\Services\Localisation;

use illuminate\Support\Facades\Facade;

class LocalisationService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "Localisation";
    }
}