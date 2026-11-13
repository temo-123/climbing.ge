<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\NotificationPreferenceService;
use Illuminate\Http\Request;

/**
 * One-click "unsubscribe from all notification e-mails" — the link in every notification mail
 * and the List-Unsubscribe target. No login: the HMAC in the URL identifies the user.
 *   GET  → a small page with a button (GET never changes anything: mail scanners open links)
 *   POST → turns all notification mail off (`action=on` turns it back on). Mail clients send
 *          `List-Unsubscribe=One-Click` (RFC 8058) and only need a 2xx.
 * docs/FUNCTIONS_DOCS/user/notification_mute.md
 */
class UnsubscribeController extends Controller
{
    public function show(int $user_id, string $hash)
    {
        $user = $this->user_for($user_id, $hash);
        if (!$user) return $this->page('invalid', null, 404);
        return $this->page(NotificationPreferenceService::is_muted($user) ? 'already_off' : 'confirm', $user);
    }

    public function update(Request $request, int $user_id, string $hash)
    {
        $user = $this->user_for($user_id, $hash);
        if (!$user) return $this->page('invalid', null, 404);

        $on = $request->input('action') === 'on';
        NotificationPreferenceService::set_muted($user, !$on);
        return $this->page($on ? 'back_on' : 'done', $user);
    }

    private function user_for(int $user_id, string $hash): ?User
    {
        $user = User::find($user_id);
        return ($user && hash_equals(NotificationPreferenceService::unsubscribe_hash($user), $hash)) ? $user : null;
    }

    private function page(string $state, ?User $user, int $status = 200)
    {
        $ka = $user && $user->lang === 'ka';
        return response()->view('notifications.unsubscribe', [
            'state'      => $state,
            'ka'         => $ka,
            'email'      => $user ? self::mask($user->email) : null,
            'action_url' => $user ? NotificationPreferenceService::unsubscribe_url($user) : null,
            'manage_url' => \App\Mail\UserNotificationMessage::manage_alerts_url(),
        ], $status)->header('X-Robots-Tag', 'noindex');
    }

    /** n***@gmail.com — enough to recognise, not enough to leak */
    private static function mask(string $email): string
    {
        [$name, $domain] = array_pad(explode('@', $email, 2), 2, '');
        return mb_substr($name, 0, 1) . str_repeat('*', max(3, mb_strlen($name) - 1)) . '@' . $domain;
    }
}
