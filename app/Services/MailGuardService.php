<?php

namespace App\Services;

use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Mime\Address;

/**
 * Runs on EVERY outgoing mail (MessageSending, registered in AppServiceProvider):
 *
 *  1. Banned users get nothing — any To / Cc / Bcc address that belongs to a user with
 *     the `ban` role is removed; when no To is left the mail is cancelled.
 *  2. Mail images (public/images/email/*.png referenced by the theme header / footer)
 *     are embedded as CID attachments, so they show in every client even when the site
 *     URL isn't reachable (local dev, Mailtrap, image proxies).
 *
 * docs/FUNCTIONS_DOCS/global/mail_design.md
 */
class MailGuardService
{
    public function handle(MessageSending $event)
    {
        $email = $event->message;

        // 1. banned recipients
        $banned = self::banned_emails();
        if ($banned) {
            foreach (['To', 'Cc', 'Bcc'] as $kind) {
                $get = 'get' . $kind;
                $set = strtolower($kind);
                $keep = array_values(array_filter($email->$get(), fn (Address $a) => !isset($banned[strtolower($a->getAddress())])));
                if (count($keep) !== count($email->$get())) {
                    $email->getHeaders()->remove($kind);
                    if ($keep) $email->$set(...$keep);
                }
            }
            if (!count($email->getTo())) {
                return false; // nobody left → don't send
            }
        }

        // 2. embed theme images
        $html = $email->getHtmlBody();
        if (is_string($html) && str_contains($html, '/images/email/')) {
            $html = preg_replace_callback('#src="[^"]*?/images/email/([a-z0-9_\-]+\.png)"#i', function ($m) use ($email) {
                $path = public_path('images/email/' . $m[1]);
                if (!is_file($path)) return $m[0];
                $cid = 'cg-' . pathinfo($m[1], PATHINFO_FILENAME);
                static $done = [];
                $key = spl_object_id($email) . $cid;
                if (!isset($done[$key])) {
                    $email->embedFromPath($path, $cid, 'image/png');
                    $done[$key] = true;
                }
                return 'src="cid:' . $cid . '"';
            }, $html);
            $email->html($html);
        }

        return null;
    }

    /** lowercase email => true for every banned user (role slug `ban`) */
    public static function banned_emails(): array
    {
        static $cache = null;
        if ($cache !== null && !app()->runningUnitTests()) return $cache;
        $emails = DB::table('users')
            ->join('user_role', 'user_role.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'user_role.role_id')
            ->where('roles.slug', 'ban')
            ->pluck('users.email');
        return $cache = $emails->filter()->mapWithKeys(fn ($e) => [strtolower(trim($e)) => true])->all();
    }
}
