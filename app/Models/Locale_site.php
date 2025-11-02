<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
            return $record->{$locale . '_data'};
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
            $record->{$locale . '_data'} = $data;
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
