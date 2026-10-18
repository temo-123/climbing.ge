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
 * orders — retained for accounting. Ascents, reviews, product feedbacks and
 * comments are deleted unless the user chose to keep them anonymized ($keep).
 * Tables with an FK cascade to users are cleaned up by the database itself.
 */
class AccountDeletionService
{
    // Content the user may choose to keep (anonymized) instead of deleting
    const KEEPABLE = ['ascents', 'reviews', 'product_feedbacks', 'comments'];

    // Name shown on content kept from a deleted account
    const DELETED_NAME = 'Deleted user';

    /**
     * @param array $keep subset of KEEPABLE — kept content stays public without
     *                    any link to the account (no name/email, user_id = NULL)
     */
    public static function delete(User $user, array $keep = []): void
    {
        DB::transaction(function () use ($user, $keep) {
            $id = $user->id;
            $anonymous = ['name' => self::DELETED_NAME, 'surname' => '', 'email' => null];

            $user->tokens()->delete();

            // User-written content (holds name/email copies) — answers/complaints cascade
            $comment_ids = DB::table('article_comment_user')->where('user_id', $id)->pluck('comment_id');
            DB::table('article_comment_user')->where('user_id', $id)->delete();
            if (in_array('comments', $keep)) {
                DB::table('article_comments')->whereIn('id', $comment_ids)->update($anonymous);
            } else {
                DB::table('article_comments')->whereIn('id', $comment_ids)->delete();
            }

            $feedback_ids = DB::table('user_product_feedbacks')->where('user_id', $id)->pluck('feedback_id');
            DB::table('user_product_feedbacks')->where('user_id', $id)->delete();
            if (in_array('product_feedbacks', $keep)) {
                DB::table('product_feedbacks')->whereIn('id', $feedback_ids)->update($anonymous);
            } else {
                DB::table('product_feedbacks')->whereIn('id', $feedback_ids)->delete();
            }

            if (in_array('reviews', $keep)) {
                DB::table('sport_route_reviews')->where('user_id', $id)->update(['user_id' => null]);
                DB::table('mtp_reviews')->where('user_id', $id)->update(['user_id' => null]);
            } else {
                DB::table('sport_route_reviews')->where('user_id', $id)->delete();
                DB::table('mtp_reviews')->where('user_id', $id)->delete();
            }

            // summit_ascent_users links cascade with the user either way
            $ascent_ids = DB::table('summit_ascent_users')->where('user_id', $id)->pluck('ascent_id');
            if (in_array('ascents', $keep)) {
                DB::table('summit_ascents')->whereIn('id', $ascent_ids)->update($anonymous);
                DB::table('mtp_ascents')->where('user_id', $id)->update(['user_id' => null]);
            } else {
                DB::table('summit_ascents')->whereIn('id', $ascent_ids)->delete();
                DB::table('mtp_ascents')->where('user_id', $id)->delete();
            }

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
