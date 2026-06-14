<?php

namespace App\Models\Guide;

use Illuminate\Database\Eloquent\Model;

class Mtp extends Model
{
    public $table = 'mtps';

    protected $fillable = [
        "sector_id",
        "title",
        "text",
        "last_carabin",
        "height",
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
