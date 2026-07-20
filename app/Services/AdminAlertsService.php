<?php

namespace App\Services;

use App\Http\Controllers\Api\User\Admin\User\DatabaseController;
use App\Models\Guide\Comment;
use App\Models\Guide\Mtp_review;
use App\Models\Guide\Sport_route_review;
use App\Models\Shop\Product_feedback;
use App\Models\Summit\SummitAscent;
use App\Models\User;
use App\Models\User\AdminAlertView;
use Illuminate\Support\Carbon;

/**
 * Aggregates "you should look at this" alerts for the admin panel.
 *
 * Two kinds of alert:
 * - "danger" alerts (e.g. DB relation issues) always show while the underlying
 *   condition is true — there's nothing to dismiss, they resolve themselves
 *   once the issue is fixed.
 * - "info" alerts (new comments/reviews/ascents/feedback) are dismissible:
 *   each admin has their own "last viewed" timestamp per alert type
 *   (admin_alert_views), so the count only ever reflects what's new to them.
 */
class AdminAlertsService
{
    /**
     * key => [permission subject, permission action, model class, icon, admin route name]
     */
    private const TRACKED_TYPES = [
        'new_comments'         => ['comment', 'show', Comment::class, 'fa-comment-o', 'commentsAndReviewsList'],
        'new_product_feedback' => ['product_feedback', 'show', Product_feedback::class, 'fa-star-o', 'commentsAndReviewsList'],
        'new_mtp_reviews'      => ['comment', 'show', Mtp_review::class, 'fa-map-signs', 'allRoutesReviews'],
        'new_route_reviews'    => ['routes_rewiew', 'show', Sport_route_review::class, 'fa-road', 'allRoutesReviews'],
        'new_ascents'          => ['summit', 'show', SummitAscent::class, 'fa-flag-checkered', 'summitList'],
    ];

    public function getAlerts(User $user): array
    {
        $alerts = [];

        if ($user->hasPermissionFor('database', 'show')) {
            $issueCount = count(app(DatabaseController::class)->detectIssues());
            if ($issueCount > 0) {
                $alerts[] = $this->item('db_relation_issues', 'danger', 'fa-database', $issueCount, 'multimedia', false);
            }
        }

        foreach (self::TRACKED_TYPES as $key => [$subject, $action, $modelClass, $icon, $route]) {
            if (!$user->hasPermissionFor($subject, $action)) {
                continue;
            }

            $count = $modelClass::where('created_at', '>', $this->sinceFor($user, $key))->count();
            if ($count > 0) {
                $alerts[] = $this->item($key, 'info', $icon, $count, $route, true);
            }
        }

        return $alerts;
    }

    public function markViewed(User $user, string $key): void
    {
        if (!array_key_exists($key, self::TRACKED_TYPES)) {
            return;
        }

        AdminAlertView::updateOrCreate(
            ['user_id' => $user->id, 'alert_key' => $key],
            ['last_viewed_at' => now()]
        );
    }

    private function sinceFor(User $user, string $key): Carbon
    {
        $view = AdminAlertView::where('user_id', $user->id)->where('alert_key', $key)->first();

        if ($view && $view->last_viewed_at) {
            return $view->last_viewed_at;
        }

        // First time this admin sees this alert type: start the counter a few
        // days back instead of dumping the entire historical backlog as "new".
        $baseline = now()->subDays(3);
        AdminAlertView::updateOrCreate(
            ['user_id' => $user->id, 'alert_key' => $key],
            ['last_viewed_at' => $baseline]
        );

        return $baseline;
    }

    private function item(string $key, string $severity, string $icon, int $count, string $route, bool $dismissible): array
    {
        return compact('key', 'severity', 'icon', 'count', 'route', 'dismissible');
    }
}
