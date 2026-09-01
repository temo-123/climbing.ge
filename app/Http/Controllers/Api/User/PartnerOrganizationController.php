<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\PartnerOrganization\PartnerOrganizationMember;

use Auth;

class PartnerOrganizationController extends Controller
{
    // Self-service status check for the logged-in user — not permission gated,
    // it only ever exposes the caller's own membership + fellow-member list.
    public function my_status()
    {
        $member = PartnerOrganizationMember::with('organization')
            ->where('user_id', Auth::id())
            ->first();

        if (!$member) {
            return response()->json(['is_member' => false]);
        }

        $other_members = PartnerOrganizationMember::where('organization_id', $member->organization_id)
            ->where('id', '!=', $member->id)
            ->orderBy('name')
            ->get(['id', 'name', 'surname', 'email', 'phone_number']);

        return response()->json([
            'is_member' => true,
            'member' => $member,
            'organization' => $member->organization,
            'other_members' => $other_members,
        ]);
    }
}
