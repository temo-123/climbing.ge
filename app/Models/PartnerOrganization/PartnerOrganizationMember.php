<?php

namespace App\Models\PartnerOrganization;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class PartnerOrganizationMember extends Model
{
    public $table = 'partner_organization_members';

    protected $fillable = [
        'organization_id',
        'name',
        'surname',
        'email',
        'phone_number',
        'user_id',
    ];

    public function organization()
    {
        return $this->belongsTo(PartnerOrganization::class, 'organization_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
