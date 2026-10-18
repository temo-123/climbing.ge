<?php

namespace App\Models\Guide;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class MtpAscent extends Model
{
    use HasFactory;

    // lead | second | alternate (who led the pitches)
    const STYLES = ['lead', 'second', 'alternate'];

    protected $fillable = [
        'mtp_id',
        'user_id',
        'ascent_date',
        'ascent_time',
        'ascent_style',
        'comment',
        'photo',
    ];

    protected $casts = [
        'ascent_date' => 'date:Y-m-d',
    ];

    public function mtp()
    {
        return $this->belongsTo(Mtp::class, 'mtp_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
