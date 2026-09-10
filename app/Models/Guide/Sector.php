<?php


namespace App\Models\Guide;



use Illuminate\Database\Eloquent\Model;
use App\Models\Guide\Sector_local_image_sector;
use App\Models\Guide\Sector_image;
use App\Models\Guide\SectorLocalImagesJson;

class Sector extends Model
{
    // Real PHP property (not an Eloquent attribute) so setting it doesn't get
    // picked up by getDirty()/the UPDATE query. Read by SectorObserver::updated()
    // to decide whether/how the admin opted in to a notification for this save:
    // 'none' (default), 'update', or 'new' (re-announce as if newly published).
    public $notifyMode = 'none';

    // Without these casts, Eloquent returns the underlying int(1)/int(0)/null
    // columns as raw PHP ints, which JSON-encode as JS numbers (1/0), not
    // booleans. The admin edit form's checkboxes (plain `v-model="data.x"`
    // with a static `value="1"`, no `true-value`) use Vue's default checkbox
    // equality check, which falls back to `String(a) === String(b)` for a
    // non-boolean model value — `String(1) === String(true)` is `"1" ===
    // "true"`, always false. So every one of these checkboxes silently
    // rendered unchecked on Edit (loading a real sector with real 1s in the
    // DB) while looking fine on Add (which starts from empty/null, so
    // "unchecked" was already the correct state and the bug was invisible).
    // Casting to 'boolean' here makes the API return real JS true/false,
    // which Vue's checkbox binding handles correctly — no frontend change
    // needed.
    protected $casts = [
        'all_day_in_shade' => 'boolean',
        'all_day_in_sun' => 'boolean',
        'in_the_shade_afternoon' => 'boolean',
        'in_the_shade_befornoon' => 'boolean',
        'in_shade_after_10' => 'boolean',
        'in_shade_after_15' => 'boolean',
        'slabby' => 'boolean',
        'vertical' => 'boolean',
        'overhang' => 'boolean',
        'roof' => 'boolean',
        'for_family' => 'boolean',
        'for_kids' => 'boolean',
        'is_helmet' => 'boolean',
    ];

    protected $fillable = [
        "name",
        "text",
        "ka_description",
        "us_description",

        'all_day_in_shade',
        'all_day_in_sun',
        'in_the_shade_afternoon',
        'in_the_shade_befornoon',
        'in_shade_after_10',
        'in_shade_after_15',


        'slabby',
        'vertical',
        'roof',
        'overhang',
        'is_helmet',

        "article_id",
    ];

    public function article()
    {
        return $this->hasOne(Article::class, "id", "article_id");
    }

    public function routes()
    {
        return $this->hasMany(Route::class)->orderBy('num');
    }

    public function sport_routes()
    {
        return $this->hasMany(Route::class,)->where('category', '!=', 'bouldering')->orderBy('num');
    }

    public function boulder_routes()
    {
        return $this->hasMany(Route::class)->where('category', '=', 'bouldering')->orderBy('num');
    }

    public function images()
    {
        return $this->hasMany(Sector_image::class)->orderBy('num');
    }

    public function mtps()
    {
        return $this->hasMany(Mtp::class)->orderBy('num');
    }

	// public function sector_rocks_images()
	// {
	// 	return $this->hasOhe(Spot_rocks_image_sector::class, 'sector_id');
	// }

	public function sector_local_image()
	{
		// return $this->hasOhe(Spot_rocks_image_sector::class, 'sector_id');
        return $this->belongsToMany(Sector_local_image::class, 'sector_local_image_sector', 'sector_id', 'image_id');
	}

	public function sector_local_image_relation()
	{
        return $this->hasMany(Sector_local_image_sector::class, 'sector_id', 'id');
	}

    public function sector_local_images_jsons()
    {
        return $this->belongsToMany(SectorLocalImagesJson::class, 'sector_local_images_json_sectors', 'sector_id', 'sect_loc_img_json_id');
    }
}
