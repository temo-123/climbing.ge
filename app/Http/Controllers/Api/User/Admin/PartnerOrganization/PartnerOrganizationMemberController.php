<?php

namespace App\Http\Controllers\Api\User\Admin\PartnerOrganization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\PartnerOrganization\PartnerOrganizationMember;
use App\Models\User;
use App\Services\PermissionService;

use Validator;

class PartnerOrganizationMemberController extends Controller
{
    public function get_all_members()
    {
        if ($auth = PermissionService::authorize('partner_organization', 'show')) return $auth;

        return PartnerOrganizationMember::with('organization')->latest('id')->get();
    }

    public function get_editing_member_data(Request $request)
    {
        if ($auth = PermissionService::authorize('partner_organization', 'show')) return $auth;

        return PartnerOrganizationMember::where('id', strip_tags($request->id))->first();
    }

    public function add_member(Request $request)
    {
        if ($auth = PermissionService::authorize('partner_organization', 'add')) return $auth;

        $validate = $this->member_validate($request->data);
        if ($validate != null) {
            return response()->json(['validation' => $validate], 422);
        }

        $member = new PartnerOrganizationMember;
        $member['organization_id'] = $request->data['organization_id'];
        $member['name'] = $request->data['name'];
        $member['surname'] = $request->data['surname'];
        $member['email'] = $request->data['email'];
        $member['phone_number'] = $request->data['phone_number'] ?? null;
        $member['user_id'] = optional(User::where('email', $request->data['email'])->first())->id;
        $member->save();

        return response()->json(['message' => 'Member created', 'id' => $member->id]);
    }

    public function edit_member(Request $request)
    {
        if ($auth = PermissionService::authorize('partner_organization', 'edit')) return $auth;

        $validate = $this->member_validate($request->data, strip_tags($request->id));
        if ($validate != null) {
            return response()->json(['validation' => $validate], 422);
        }

        $member = PartnerOrganizationMember::where('id', strip_tags($request->id))->first();
        $member['organization_id'] = $request->data['organization_id'];
        $member['name'] = $request->data['name'];
        $member['surname'] = $request->data['surname'];
        $member['email'] = $request->data['email'];
        $member['phone_number'] = $request->data['phone_number'] ?? null;
        $member['user_id'] = optional(User::where('email', $request->data['email'])->first())->id;
        $member->save();

        return response()->json(['message' => 'Member updated']);
    }

    public function del_member(Request $request)
    {
        if ($auth = PermissionService::authorize('partner_organization', 'del')) return $auth;

        PartnerOrganizationMember::where('id', strip_tags($request->id))->delete();

        return response()->json(['message' => 'Member deleted']);
    }

    public function bulk_delete(Request $request)
    {
        if ($auth = PermissionService::authorize('partner_organization', 'del')) return $auth;

        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer',
        ]);

        PartnerOrganizationMember::destroy($request->ids);

        return response()->json(['success' => true, 'count' => count($request->ids)]);
    }

    // Used by the "assign to organization" shortcut on the admin Users tab.
    public function get_user_status(Request $request, $user_id)
    {
        if ($auth = PermissionService::authorize('partner_organization', 'show')) return $auth;

        $member = PartnerOrganizationMember::with('organization')->where('user_id', strip_tags($user_id))->first();

        return response()->json([
            'is_member' => (bool) $member,
            'organization_id' => optional($member)->organization_id,
            'organization_name' => optional(optional($member)->organization)->name,
        ]);
    }

    public function assign_user(Request $request, $user_id)
    {
        if ($auth = PermissionService::authorize('partner_organization', 'add')) return $auth;

        $request->validate([
            'organization_id' => 'required|integer|exists:partner_organizations,id',
        ]);

        $user = User::find(strip_tags($user_id));
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $member = PartnerOrganizationMember::where('user_id', $user->id)->first()
            ?: PartnerOrganizationMember::where('email', $user->email)->first()
            ?: new PartnerOrganizationMember;

        $member['organization_id'] = $request->organization_id;
        $member['name'] = $user->name;
        $member['surname'] = $user->surname;
        $member['email'] = $user->email;
        $member['phone_number'] = $user->phone_number;
        $member['user_id'] = $user->id;
        $member->save();

        return response()->json(['message' => 'User assigned to organization']);
    }

    public function unassign_user(Request $request, $user_id)
    {
        if ($auth = PermissionService::authorize('partner_organization', 'del')) return $auth;

        PartnerOrganizationMember::where('user_id', strip_tags($user_id))->delete();

        return response()->json(['message' => 'User removed from organization']);
    }

    private function member_validate($data, $ignore_id = null)
    {
        $unique = 'unique:partner_organization_members,email' . ($ignore_id ? ',' . $ignore_id : '');

        $validator = Validator::make($data, [
            'organization_id' => 'required|integer|exists:partner_organizations,id',
            'name' => 'required|max:190',
            'surname' => 'required|max:190',
            'email' => 'required|email|' . $unique,
            'phone_number' => 'nullable|max:60',
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }
    }
}
