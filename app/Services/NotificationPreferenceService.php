<?php

namespace App\Services;

use App\Models\User;
use App\Models\User\user_notification;
use Illuminate\Support\Facades\DB;

/**
 * The "turn off all notification e-mails" switch (`all_off` in user_notifications.notification_type).
 *
 * Notification mail = content alerts, favourites, event reminders, follow activity, new follower,
 * back-in-stock, seasonal picks, comment replies. Account mail (verification, password, orders,
 * bookings, moderation, staff mails) is never muted.
 *
 * Enforced three times: NotificationDispatchService::sendOnce() and the seasonal audience skip
 * muted users before anything is queued, and MailGuardService drops muted recipients of any mail
 * carrying the HEADER (so a mail queued before the user muted is still stopped).
 * Docs: docs/FUNCTIONS_DOCS/user/notification_mute.md
 */
class NotificationPreferenceService
{
    public const KEY = 'all_off';

    /** header set on every notification mail; MailGuardService reads it */
    public const HEADER = 'X-CG-Notification';

    public static function is_muted(User $user): bool
    {
        $prefs = user_notification::where('user_id', $user->id)->value('notification_type');
        $prefs = is_array($prefs) ? $prefs : (json_decode((string) $prefs, true) ?: []);
        return !empty($prefs[self::KEY]);
    }

    /** lowercase email => true for every user with all notification mail off */
    public static function muted_emails(): array
    {
        return DB::table('users')
            ->join('user_notifications', 'user_notifications.user_id', '=', 'users.id')
            ->where('user_notifications.notification_type->' . self::KEY, true)
            ->pluck('users.email')
            ->filter()
            ->mapWithKeys(fn ($e) => [strtolower(trim($e)) => true])
            ->all();
    }

    public static function set_muted(User $user, bool $muted): void
    {
        $row = user_notification::firstOrNew(['user_id' => $user->id]);
        $prefs = is_array($row->notification_type) ? $row->notification_type : [];
        $prefs[self::KEY] = $muted;
        $row->notification_type = $prefs;
        $row->save();
    }

    // ── one-click unsubscribe (no login) ─────────────────────────────────────

    public static function unsubscribe_hash(User $user): string
    {
        // includes the e-mail: changing the address kills old links
        return hash_hmac('sha256', 'unsubscribe|' . $user->id . '|' . strtolower($user->email), config('app.key'));
    }

    public static function unsubscribe_url(User $user): string
    {
        return rtrim((string) config('app.url'), '/') . '/api/notifications/unsubscribe/' . $user->id . '/' . self::unsubscribe_hash($user);
    }

    /** for mails that only know the address (UserNotifications job, comment replies) */
    public static function unsubscribe_url_for_email(?string $email): ?string
    {
        if (!$email) return null;
        $user = User::where('email', $email)->first();
        return $user ? self::unsubscribe_url($user) : null;
    }

    /**
     * Headers for a notification mail: the mute marker + List-Unsubscribe. With an
     * unsubscribe URL, mail clients get RFC 8058 one-click (their own "Unsubscribe" button).
     */
    public static function add_headers($symfony_message, string $manage_url, ?string $unsubscribe_url): void
    {
        $headers = $symfony_message->getHeaders();
        $headers->addTextHeader(self::HEADER, '1');
        $headers->addTextHeader('List-Unsubscribe', $unsubscribe_url ? '<' . $unsubscribe_url . '>, <' . $manage_url . '>' : '<' . $manage_url . '>');
        if ($unsubscribe_url) {
            $headers->addTextHeader('List-Unsubscribe-Post', 'List-Unsubscribe=One-Click');
        }
    }
}
