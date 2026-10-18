<?php

namespace App\Services;

use App\Models\User;
use App\Services\Abstract\ImageControllService;
use Illuminate\Support\Facades\DB;

/**
 * Permanently deletes a user account and its personal data (Google Play User
 * Data policy) — used by POST /api/account/delete.
 *
 * Kept (detached, user_id = NULL): orders, donations and addresses used by
 * orders — retained for accounting. Tables with an FK cascade to users are
 * cleaned up by the database itself.
 */
class AccountDeletionService
{
    public static function delete(User $user): void
    {
        DB::transaction(function () use ($user) {
            $id = $user->id;

            $user->tokens()->delete();

            // User-written content (holds name/email copies) — answers/complaints cascade
            $comment_ids = DB::table('article_comment_user')->where('user_id', $id)->pluck('comment_id');
            DB::table('article_comment_user')->where('user_id', $id)->delete();
            DB::table('article_comments')->whereIn('id', $comment_ids)->delete();

            $feedback_ids = DB::table('user_product_feedbacks')->where('user_id', $id)->pluck('feedback_id');
            DB::table('user_product_feedbacks')->where('user_id', $id)->delete();
            DB::table('product_feedbacks')->whereIn('id', $feedback_ids)->delete();

            DB::table('sport_route_reviews')->where('user_id', $id)->delete();

            $ascent_ids = DB::table('summit_ascent_users')->where('user_id', $id)->pluck('ascent_id');
            DB::table('summit_ascents')->whereIn('id', $ascent_ids)->delete();

            // Tables without a cascading FK
            DB::table('carts')->where('user_id', $id)->delete();
            DB::table('favorite_products')->where('user_id', $id)->delete();
            DB::table('user_followers')->where('user_id', $id)->orWhere('following_user_id', $id)->delete();
            DB::table('non_registered_commenters')->where('email', $user->email)->delete();

            // Addresses: drop unused ones, the ones on past orders get user_id = NULL via FK
            $adres_ids = DB::table('user_adreses')->where('user_id', $id)->pluck('id');
            $used_adres_ids = DB::table('orders')->whereIn('adres_id', $adres_ids)->pluck('adres_id')
                ->merge(DB::table('user_adrese_order')->whereIn('user_adrese_id', $adres_ids)->pluck('user_adrese_id'));
            DB::table('user_adreses')->whereIn('id', $adres_ids->diff($used_adres_ids))->delete();

            DB::table('orders')->where('user_id', $id)->update(['user_id' => null]);

            if ($user->image) {
                ImageControllService::image_delete('images/user_profil_img/'.$user->image.'_img/', $user, 'image');
            }

            $user->delete();
        });
    }
}
