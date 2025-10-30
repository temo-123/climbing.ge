<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Site extends Model
{
    use Notifiable;

    public function getLocaleData($locale)
    {
        $localeData = [];
        $localeRecords = Locale_site::all();

        foreach ($localeRecords as $record) {
            $localeData[$record->slug] = $record->{$locale . '_data'};
        }

        return $localeData;
    }

    public function getUsSite()
    {
        return $this->getLocaleData('us');
    }

    public function getKaSite()
    {
        return $this->getLocaleData('ka');
    }
}
