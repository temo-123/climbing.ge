<?php

namespace App\Http\Controllers\Api\User\Admin\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Guide\Mtp_review;
use App\Services\ReCaptchaV3Service;
use App\Services\PermissionService;
use Auth;
use Illuminate\Support\Facades\DB;

class MtpReitingController extends Controller
{
    public function create_mtp_review(Request $request, $mtp_id)
    {
        $captcha = new ReCaptchaV3Service();
        if ($captcha->isConfigured()) {
            $token = $request->input('recaptcha_token');
            if (!$token || !$captcha->verify($token, $request->ip(), 0.5)) {
                return response()->json(['message' => 'reCAPTCHA verification failed. Please try again.'], 422);
            }
        }

        $user = $request->user();

        $already = Mtp_review::where('mtp_id', $mtp_id)
            ->where('user_id', $user->id)
            ->count();

        if ($already > 0) {
            return response()->json([
                'already_reviewed' => true,
                'message' => 'You already have a review for this route.',
            ], 422);
        }

        $review = new Mtp_review();
        $review->mtp_id  = $mtp_id;
        $review->user_id = $user->id;
        $review->stars   = $request->input('stars');
        $review->text    = $request->input('text');
        $review->save();

        return response()->json(['success' => true, 'message' => 'Thank you for your feedback!']);
    }

    public function get_all_mtp_reviews_admin()
    {
        return Mtp_review::with(['mtp', 'user'])->get()->map(function ($r) {
            return ['review' => $r, 'mtp' => $r->mtp, 'user' => $r->user];
        });
    }

    public function get_actyve_mtp_review($review_id)
    {
        return Mtp_review::find($review_id);
    }

    public function edit_mtp_review(Request $request, $review_id)
    {
        $hasAdminPermission = PermissionService::authorize('comment', 'edit') === null;
        if ($hasAdminPermission) {
            $review = Mtp_review::find($review_id);
        } else {
            $review = Mtp_review::where('id', $review_id)->where('user_id', Auth::id())->first();
        }
        if (!$review) return response()->json(['error' => 'Not found'], 404);
        if (!$hasAdminPermission && $review->admin_hidden) {
            return response()->json(['error' => 'Admin hidden', 'admin_hidden' => true], 403);
        }
        $review->stars = $request->input('stars');
        $review->text  = $request->input('text');
        $review->save();
        return response()->json(['success' => true]);
    }

    public function user_hide_mtp_review($review_id)
    {
        $review = Mtp_review::where('id', $review_id)->where('user_id', Auth::id())->first();
        if (!$review) return response()->json(['error' => 'Not found'], 404);
        if ($review->admin_hidden) return response()->json(['error' => 'Admin hidden', 'admin_hidden' => true], 403);
        $review->published = 0;
        $review->save();
        return response()->json(['success' => true]);
    }

    public function user_show_mtp_review($review_id)
    {
        $review = Mtp_review::where('id', $review_id)->where('user_id', Auth::id())->first();
        if (!$review) return response()->json(['error' => 'Not found'], 404);
        if ($review->admin_hidden) return response()->json(['error' => 'Hidden by admin', 'admin_hidden' => true], 403);
        $review->published = 1;
        $review->save();
        return response()->json(['success' => true]);
    }

    public function hide_mtp_review(Request $request, $review_id)
    {
        $review = Mtp_review::find($review_id);
        if ($review) {
            $review->published = 0;
            $review->admin_hidden = 1;
            $review->hidden_reason = $request->input('hidden_reason');
            $review->hidden_reason_text = $request->input('hidden_reason_text');
            $review->save();
        }
        return response()->json(['success' => true]);
    }

    public function del_mtp_review($review_id)
    {
        $hasAdminPermission = PermissionService::authorize('comment', 'del') === null;

        if ($hasAdminPermission) {
            $review = Mtp_review::find($review_id);
        } else {
            $review = Mtp_review::where('id', $review_id)->where('user_id', Auth::id())->first();
        }

        if (!$review) return response()->json(['success' => true]);
        if (!$hasAdminPermission && $review->admin_hidden) {
            return response()->json(['error' => 'Admin hidden', 'admin_hidden' => true], 403);
        }
        $review->delete();
        return response()->json(['success' => true]);
    }

    /**
     * Bulk version of del_mtp_review. Replicates the exact same branching
     * logic as the single-item method: admins (has 'comment','del' permission)
     * can delete any review by id; non-admins can only delete their own, and
     * never one an admin has hidden. The admin-permission check happens once
     * before the loop (not per id). Missing / not-owned / admin-hidden ids
     * are silently skipped rather than surfacing individual errors, since a
     * bulk operation can't return one status per id.
     */
    public function bulk_del_mtp_review(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer',
        ]);

        $hasAdminPermission = PermissionService::authorize('comment', 'del') === null;

        DB::transaction(function () use ($request, $hasAdminPermission) {
            foreach ($request->ids as $id) {
                if ($hasAdminPermission) {
                    $review = Mtp_review::find($id);
                } else {
                    $review = Mtp_review::where('id', $id)->where('user_id', Auth::id())->first();
                }

                if (!$review) continue;
                if (!$hasAdminPermission && $review->admin_hidden) continue;

                $review->delete();
            }
        });

        return response()->json(['success' => true, 'count' => count($request->ids)]);
    }
}
