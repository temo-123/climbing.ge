<?php

namespace App\Http\Controllers\Api\User\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

use App\Models\User;
use App\Models\User\user_notification;
use App\Models\User\ContentNotificationLog;
use App\Models\User\Following_users;

use App\Services\PermissionService;

class NotificationAnalyticsController extends Controller
{
    private const PREFERENCE_KEYS = [
        'favorite_outdoor',
        'favorite_product',
        'favorite_film',
        'interested_event',
        'news',
        'add_new_sector',
        'add_new_outdoor_spot',
        'add_new_ice_spot',
        'add_new_techtip',
        'add_new_gym',
        'add_new_product',
        'add_new_service',
        'add_new_tour',
        'add_new_mount',
        'add_new_summit',
        'add_new_blog_post',
        'add_new_film',
        'sector_updated',
        'outdoor_spot_updated',
        'ice_spot_updated',
        'techtip_updated',
        'gym_updated',
        'news_updated',
        'product_updated',
        'service_updated',
        'tour_updated',
        'mount_updated',
        'summit_updated',
        'blog_post_updated',
        'film_updated',
    ];

    private const CONTENT_TYPE_LABELS = [
        'App\\Models\\Guide\\Article' => 'Article',
        'App\\Models\\Guide\\Sector' => 'Sector',
        'App\\Models\\Guide\\Mount' => 'Mount',
        'App\\Models\\Guide\\Event' => 'Event',
        'App\\Models\\Shop\\Product' => 'Product',
        'App\\Models\\Shop\\Service' => 'Service',
        'App\\Models\\Shop\\Tour' => 'Tour',
        'App\\Models\\Blog\\Post' => 'Blog Post',
        'App\\Models\\Summit\\Summit' => 'Summit',
        'App\\Models\\Films\\Film' => 'Film',
    ];

    private const NOTIFICATION_KEY_LABELS = [
        'new_content' => 'New Content',
        'favorite_publish' => 'Favorite Item Published',
        'event_reminder' => 'Event Reminder',
        'event_reminder_1month' => 'Event Reminder (1 Month Before)',
        'event_reminder_2weeks' => 'Event Reminder (2 Weeks Before)',
        'event_reminder_1week' => 'Event Reminder (1 Week Before)',
        'event_reminder_1day' => 'Event Reminder (1 Day Before)',
        'content_updated' => 'Content Updated',
        'favorite_updated' => 'Favorite Item Edited',
    ];

    private const PREFERENCE_LABELS = [
        'favorite_outdoor' => 'Favorite Outdoor Updates',
        'favorite_product' => 'Favorite Product Updates',
        'favorite_film' => 'Favorite Film Updates',
        'interested_event' => 'Event Reminders',
        'news' => 'News',
        'add_new_sector' => 'New Sector',
        'add_new_outdoor_spot' => 'New Outdoor Article',
        'add_new_ice_spot' => 'New Ice Climbing Article',
        'add_new_techtip' => 'New Tech Tip',
        'add_new_gym' => 'New Gym Article',
        'add_new_product' => 'New Product',
        'add_new_service' => 'New Service',
        'add_new_tour' => 'New Tour',
        'add_new_mount' => 'New Mountain',
        'add_new_summit' => 'New Summit',
        'add_new_blog_post' => 'New Blog Post',
        'add_new_film' => 'New Film',
        'sector_updated' => 'Sector Updated',
        'outdoor_spot_updated' => 'Outdoor Article Updated',
        'ice_spot_updated' => 'Ice Climbing Article Updated',
        'techtip_updated' => 'Tech Tip Updated',
        'gym_updated' => 'Gym Article Updated',
        'news_updated' => 'News Article Updated',
        'product_updated' => 'Product Updated',
        'service_updated' => 'Service Updated',
        'tour_updated' => 'Tour Updated',
        'mount_updated' => 'Mountain Updated',
        'summit_updated' => 'Summit Updated',
        'blog_post_updated' => 'Blog Post Updated',
        'film_updated' => 'Film Updated',
    ];

    private const SERVICE_LABELS = [
        'guid' => 'Guide',
        'shop' => 'Shop',
        'blog' => 'Blog',
        'summit' => 'Summit',
        'films' => 'Films',
    ];

    public function get_summary(Request $request)
    {
        if ($auth = PermissionService::authorize('user_notification', 'show')) return $auth;

        $total_users = User::count();
        $users_with_preferences = user_notification::whereNotNull('notification_type')->count();

        return response()->json([
            'total_sent' => ContentNotificationLog::count(),
            'sent_last_7_days' => ContentNotificationLog::where('sent_at', '>=', now()->subDays(7))->count(),
            'sent_last_30_days' => ContentNotificationLog::where('sent_at', '>=', now()->subDays(30))->count(),
            'distinct_users_notified' => ContentNotificationLog::distinct('user_id')->count('user_id'),
            'total_users' => $total_users,
            'users_with_preferences_set' => $users_with_preferences,
        ]);
    }

    public function get_volume_over_time(Request $request, $period)
    {
        if ($auth = PermissionService::authorize('user_notification', 'show')) return $auth;

        $startDate = null;
        switch ($period) {
            case '30days': $startDate = now()->subDays(30); break;
            case '1month': $startDate = now()->subMonth(); break;
            case '3months': $startDate = now()->subMonths(3); break;
            case '6months': $startDate = now()->subMonths(6); break;
            case '1year': $startDate = now()->subYear(); break;
            case 'all': break;
            default: return response()->json(['error' => 'Invalid period'], 400);
        }

        $query = ContentNotificationLog::query();
        if ($startDate) {
            $query->where('sent_at', '>=', $startDate);
        }

        if (in_array($period, ['30days', '1month'])) {
            $rows = $query->selectRaw('DATE(sent_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            $data = [['Date', 'Notifications']];
            foreach ($rows as $row) {
                $data[] = [$row->date, (int) $row->count];
            }
        } else {
            $rows = $query->selectRaw('YEAR(sent_at) as year, MONTH(sent_at) as month, COUNT(*) as count')
                ->groupByRaw('YEAR(sent_at), MONTH(sent_at)')
                ->orderByRaw('YEAR(sent_at), MONTH(sent_at)')
                ->get();

            $data = [['Month', 'Notifications']];
            foreach ($rows as $row) {
                $data[] = [sprintf('%04d-%02d', $row->year, $row->month), (int) $row->count];
            }
        }

        return response()->json($data);
    }

    public function get_breakdown_by_key(Request $request)
    {
        if ($auth = PermissionService::authorize('user_notification', 'show')) return $auth;

        $rows = ContentNotificationLog::selectRaw('notification_key, COUNT(*) as count')
            ->groupBy('notification_key')
            ->get();

        $data = [['Type', 'Count']];
        foreach ($rows as $row) {
            $data[] = [self::NOTIFICATION_KEY_LABELS[$row->notification_key] ?? $row->notification_key, (int) $row->count];
        }

        return response()->json($data);
    }

    public function get_breakdown_by_content_type(Request $request)
    {
        if ($auth = PermissionService::authorize('user_notification', 'show')) return $auth;

        $rows = ContentNotificationLog::selectRaw('notifiable_type, COUNT(*) as count')
            ->groupBy('notifiable_type')
            ->get();

        $data = [['Content Type', 'Count']];
        foreach ($rows as $row) {
            $data[] = [self::CONTENT_TYPE_LABELS[$row->notifiable_type] ?? $row->notifiable_type, (int) $row->count];
        }

        return response()->json($data);
    }

    public function get_preference_adoption(Request $request)
    {
        if ($auth = PermissionService::authorize('user_notification', 'show')) return $auth;

        $total_users = User::count();

        $adoption = [];
        foreach (self::PREFERENCE_KEYS as $key) {
            $count = user_notification::where('notification_type->' . $key, true)->count();
            $adoption[] = [
                'key' => $key,
                'label' => self::PREFERENCE_LABELS[$key] ?? $key,
                'enabled_count' => $count,
                'total_users' => $total_users,
                'percentage' => $total_users > 0 ? round(($count / $total_users) * 100, 1) : 0,
            ];
        }

        return response()->json($adoption);
    }

    public function get_recent(Request $request)
    {
        if ($auth = PermissionService::authorize('user_notification', 'show')) return $auth;

        $limit = (int) ($request->limit ?? 50);

        $rows = ContentNotificationLog::with('user:id,name,surname,email')
            ->latest('sent_at')
            ->limit(min($limit, 200))
            ->get();

        $data = $rows->map(function ($row) {
            return [
                'id' => $row->id,
                'user_email' => $row->user->email ?? '—',
                'notification_key' => self::NOTIFICATION_KEY_LABELS[$row->notification_key] ?? $row->notification_key,
                'content_type' => self::CONTENT_TYPE_LABELS[$row->notifiable_type] ?? $row->notifiable_type,
                'notifiable_id' => $row->notifiable_id,
                'sent_at' => $row->sent_at,
            ];
        });

        return response()->json($data);
    }

    public function get_registered_subscribers(Request $request)
    {
        if ($auth = PermissionService::authorize('user_notification', 'show')) return $auth;

        // user_notifications should be 1 row per user, but nothing enforces that at the
        // DB level and legacy data has duplicates for some accounts (pre-dating the
        // notification_type column's int->json migration). updateOrCreate()/firstOrNew()
        // elsewhere in the app always resolve to the lowest-id row for a given user_id,
        // so that's the one treated as canonical here too — ordering by id ascending
        // before unique() keeps the first (lowest-id) row per user and drops the rest.
        $rows = user_notification::with('user:id,name,surname,email,email_verified_at')
            ->orderBy('id')
            ->get()
            ->unique('user_id');

        $data = $rows->filter(fn ($row) => $row->user)->map(function ($row) {
            $types = is_array($row->notification_type) ? $row->notification_type : [];

            $preferences = collect(self::PREFERENCE_KEYS)->map(fn ($key) => [
                'key' => $key,
                'label' => self::PREFERENCE_LABELS[$key] ?? $key,
                'enabled' => ($types[$key] ?? false) === true || ($types[$key] ?? false) === 1,
            ]);

            return [
                'id' => $row->user_id,
                'name' => trim($row->user->name . ' ' . $row->user->surname),
                'email' => $row->user->email,
                'email_verified' => !is_null($row->user->email_verified_at),
                'preferences' => $preferences,
            ];
        })->values();

        return response()->json($data);
    }

    public function verify_subscriber(Request $request, $user_id)
    {
        if ($auth = PermissionService::authorize('user_notification', 'manage')) return $auth;

        $user = User::find($user_id);
        if (!$user) {
            return response()->json(['message' => 'Not found'], 404);
        }

        if (is_null($user->email_verified_at)) {
            $user->email_verified_at = now();
            $user->save();
        }

        return response()->json(['email_verified' => true]);
    }

    public function subscribe_all(Request $request, $user_id)
    {
        if ($auth = PermissionService::authorize('user_notification', 'manage')) return $auth;

        $user = User::find($user_id);
        if (!$user) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $allEnabled = array_fill_keys(self::PREFERENCE_KEYS, true);

        user_notification::updateOrCreate(
            ['user_id' => $user_id],
            ['notification_type' => $allEnabled]
        );

        $preferences = collect(self::PREFERENCE_KEYS)->map(fn ($key) => [
            'key' => $key,
            'label' => self::PREFERENCE_LABELS[$key] ?? $key,
            'enabled' => true,
        ]);

        return response()->json(['preferences' => $preferences]);
    }

    public function get_guest_followers(Request $request)
    {
        if ($auth = PermissionService::authorize('user_notification', 'show')) return $auth;

        $rows = Following_users::latest('id')->get();

        $data = $rows->map(function ($row) {
            return [
                'id' => $row->id,
                'email' => $row->email,
                'service' => self::SERVICE_LABELS[$row->service] ?? ($row->service ?: '—'),
                'created_at' => $row->created_at,
            ];
        });

        return response()->json($data);
    }

    public function del_guest_follower(Request $request, $id)
    {
        if ($auth = PermissionService::authorize('user_notification', 'del')) return $auth;

        $follower = Following_users::find($id);
        if (!$follower) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $follower->delete();

        return response()->json(['message' => 'Deleted']);
    }

    public function get_failed_jobs(Request $request)
    {
        if ($auth = PermissionService::authorize('user_notification', 'show')) return $auth;

        $rows = DB::table('failed_jobs')
            ->where('queue', 'emails')
            ->orderByDesc('id')
            ->limit(100)
            ->get();

        $data = $rows->map(function ($row) {
            $payload = json_decode($row->payload, true);
            $command = $payload['data']['command'] ?? '';

            preg_match('/"email";s:\d+:"([^"]*)"/', $command, $emailMatch);
            preg_match('/"subject";s:\d+:"([^"]*)"/', $command, $subjectMatch);

            return [
                'id' => $row->id,
                'email' => $emailMatch[1] ?? '—',
                'subject' => $subjectMatch[1] ?? '—',
                'error' => strtok($row->exception, "\n") ?: $row->exception,
                'failed_at' => $row->failed_at,
            ];
        });

        return response()->json($data);
    }

    public function retry_failed_job(Request $request, $id)
    {
        if ($auth = PermissionService::authorize('user_notification', 'manage')) return $auth;

        $job = DB::table('failed_jobs')->where('id', $id)->first();
        if (!$job) {
            return response()->json(['message' => 'Not found'], 404);
        }

        Artisan::call('queue:retry', ['id' => [$id]]);

        return response()->json(['message' => 'Retried']);
    }

    public function retry_all_failed_jobs(Request $request)
    {
        if ($auth = PermissionService::authorize('user_notification', 'manage')) return $auth;

        Artisan::call('queue:retry', ['id' => ['all']]);

        return response()->json(['message' => 'Retried all']);
    }

    public function del_failed_job(Request $request, $id)
    {
        if ($auth = PermissionService::authorize('user_notification', 'manage')) return $auth;

        $deleted = DB::table('failed_jobs')->where('id', $id)->delete();
        if (!$deleted) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json(['message' => 'Deleted']);
    }
}
