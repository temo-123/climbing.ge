<?php

namespace App\Http\Controllers\Api\User\Admin\PartnerOrganization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\PartnerOrganization\PartnerOrganization;
use App\Services\PermissionService;

use Validator;

class PartnerOrganizationController extends Controller
{
    public function get_all_organizations()
    {
        if ($auth = PermissionService::authorize('partner_organization', 'show')) return $auth;

        return PartnerOrganization::latest('id')->get();
    }

    public function get_editing_organization_data(Request $request)
    {
        if ($auth = PermissionService::authorize('partner_organization', 'show')) return $auth;

        return PartnerOrganization::where('id', strip_tags($request->id))->first();
    }

    public function add_organization(Request $request)
    {
        if ($auth = PermissionService::authorize('partner_organization', 'add')) return $auth;

        $validate = $this->organization_validate($request->data);
        if ($validate != null) {
            return response()->json(['validation' => $validate], 422);
        }

        $organization = new PartnerOrganization;
        $organization['name'] = $request->data['name'];
        $organization['discount'] = $request->data['discount'];
        $organization->save();

        return response()->json(['message' => 'Organization created', 'id' => $organization->id]);
    }

    public function edit_organization(Request $request)
    {
        if ($auth = PermissionService::authorize('partner_organization', 'edit')) return $auth;

        $validate = $this->organization_validate($request->data);
        if ($validate != null) {
            return response()->json(['validation' => $validate], 422);
        }

        $organization = PartnerOrganization::where('id', strip_tags($request->id))->first();
        $organization['name'] = $request->data['name'];
        $organization['discount'] = $request->data['discount'];
        $organization->save();

        return response()->json(['message' => 'Organization updated']);
    }

    public function del_organization(Request $request)
    {
        if ($auth = PermissionService::authorize('partner_organization', 'del')) return $auth;

        PartnerOrganization::where('id', strip_tags($request->id))->delete();

        return response()->json(['message' => 'Organization deleted']);
    }

    public function bulk_delete(Request $request)
    {
        if ($auth = PermissionService::authorize('partner_organization', 'del')) return $auth;

        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer',
        ]);

        PartnerOrganization::destroy($request->ids);

        return response()->json(['success' => true, 'count' => count($request->ids)]);
    }

    private function organization_validate($data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|max:190',
            'discount' => 'required|numeric|min:0|max:100',
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }
    }
}
