<?php

namespace App\Models\Guide;

use Illuminate\Database\Eloquent\Model;

class Mtp extends Model
{
    public $table = 'mtps';

    protected $fillable = [
        "sector_id",
        "name",
        "text_us",
        "text_ka",
        "last_carabin",
        "height",
        "author",
        "first_ascent",
        "first_ascent_date",
    ];

    public function pitchs()
    {
        return $this->hasMany(Mtp_pitch::class);
    }

    public function review()
    {
        return $this->hasMany(Mtp_review::class);
    }
}
