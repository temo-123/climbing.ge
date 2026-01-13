<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Schema;

class Site extends Model
{
    use Notifiable;

    protected $fillable = ['us_info_id', 'ka_info_id'];

    public function getLocaleData($locale)
    {
        $localeData = [];
        $localeRecords = Locale_site::all();

        // Map locale codes to database column names
        $localeMap = [
            'en' => 'us_data',
            'ka' => 'ka_data',
            'us' => 'us_data',
        ];

        // Get the actual column name, default to 'us_data' for unknown locales
        $columnName = $localeMap[$locale] ?? ($locale . '_data');

        // Check if the column exists to avoid errors
        $schema = Schema::getColumnListing('locale_sites');
        if (!in_array($columnName, $schema)) {
            $columnName = 'us_data'; // Fallback to us_data
        }

        foreach ($localeRecords as $record) {
            $localeData[$record->slug] = $record->{$columnName};
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
