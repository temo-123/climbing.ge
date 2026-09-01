<?php

namespace App\Models\PartnerOrganization;

use Illuminate\Database\Eloquent\Model;

class PartnerOrganization extends Model
{
    public $table = 'partner_organizations';

    protected $fillable = [
        'name',
        'discount',
    ];

    public function members()
    {
        return $this->hasMany(PartnerOrganizationMember::class, 'organization_id');
    }
}
