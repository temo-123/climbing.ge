<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Locale_site extends Model
{
    use HasFactory;

    protected $fillable = ['slug', 'ka_data', 'us_data'];

    /**
     * Get the site that owns the locale data.
     */
    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * Get locale data by slug and locale.
     *
     * @param string $slug
     * @param string $locale
     * @return string|null
     */
    public static function getDataBySlug($slug, $locale = 'en')
    {
        $record = self::where('slug', $slug)->first();
        if ($record) {
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

            return $record->{$columnName};
        }
        return null;
    }

    /**
     * Update locale data by slug.
     *
     * @param string $slug
     * @param string $locale
     * @param string $data
     * @return bool
     */
    public static function updateDataBySlug($slug, $locale, $data)
    {
        $record = self::where('slug', $slug)->first();
        if ($record) {
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

            $record->{$columnName} = $data;
            $record->save();
            return true;
        }
        return false;
    }

    /**
     * Create or update locale data.
     *
     * @param string $slug
     * @param array $data
     * @return \App\Models\Locale_site
     */
    public static function createOrUpdate($slug, $data)
    {
        $record = self::where('slug', $slug)->first();
        if ($record) {
            $record->update($data);
            return $record;
        } else {
            $data['slug'] = $slug;
            return self::create($data);
        }
    }
}
