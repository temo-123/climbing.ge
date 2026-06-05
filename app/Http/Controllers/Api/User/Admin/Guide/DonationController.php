<?php

namespace App\Http\Controllers\Api\User\Admin\Guide;

use App\Http\Controllers\Controller;
use App\Models\Guide\Donation;
use App\Services\PermissionService;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function get_all_donations(Request $request)
    {
        if ($auth = PermissionService::authorize('donation', 'show')) return $auth;

        return Donation::with('user:id,name,surname,email')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function get_donation(Request $request, $id)
    {
        if ($auth = PermissionService::authorize('donation', 'show')) return $auth;

        return Donation::with('user:id,name,surname,email')->findOrFail($id);
    }

    public function update_status(Request $request, $id)
    {
        if ($auth = PermissionService::authorize('donation', 'edit')) return $auth;

        $request->validate(['status' => 'required|in:pending,processing,paid,failed,expired,declined']);
        $donation = Donation::findOrFail($id);
        $donation->update(['status' => $request->status]);
        return $donation;
    }

    public function del_donation(Request $request, $id)
    {
        if ($auth = PermissionService::authorize('donation', 'del')) return $auth;

        Donation::findOrFail($id)->delete();
    }
}
