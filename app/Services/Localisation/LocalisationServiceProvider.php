<?php
namespace App\Services\Localisation;

use Illuminate\Support\ServiceProvider;

class LocalisationServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind("Localisation", 'App\Services\Localisation\Localisation');
    }
}