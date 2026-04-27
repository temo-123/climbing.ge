<?php

namespace App\Http\Controllers\Api\User\Admin\Guide;

use App\Http\Controllers\Controller;
use App\Models\Guide\Donation;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function get_all_donations(Request $request)
    {
        return Donation::with('user:id,name,surname,email')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function get_donation(Request $request, $id)
    {
        return Donation::with('user:id,name,surname,email')->findOrFail($id);
    }

    public function update_status(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:pending,processing,paid,failed,expired,declined']);
        $donation = Donation::findOrFail($id);
        $donation->update(['status' => $request->status]);
        return $donation;
    }

    public function del_donation(Request $request, $id)
    {
        Donation::findOrFail($id)->delete();
    }
}
