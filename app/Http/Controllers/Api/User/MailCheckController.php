<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Webklex\IMAP\Facades\Client;

class MailCheckController extends Controller
{
    public function get_unread_count(Request $request)
    {
        if ($auth = PermissionService::authorize('mail', 'show')) return $auth;

        try {
            $client = Client::account('default');
            $client->connect();

            $folder = $client->getFolder('INBOX');
            $unseen = $folder->query()->unseen()->count();

            $client->disconnect();

            return response()->json([
                'unseen'      => (int) $unseen,
                'webmail_url' => env('IMAP_WEBMAIL_URL', ''),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error'  => 'Cannot connect to mail server',
                'detail' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mailbox folders exposed in the admin mail widget. Keyed by the real
     * IMAP folder path on this account so the frontend never has to guess it.
     */
    private const FOLDERS = [
        'INBOX' => 'Inbox',
        'Sent'  => 'Sent',
        'Trash' => 'Trash',
    ];

    public function get_folders(Request $request)
    {
        if ($auth = PermissionService::authorize('mail', 'show')) return $auth;

        $folders = array_map(
            fn ($path, $label) => ['path' => $path, 'label' => $label],
            array_keys(self::FOLDERS),
            self::FOLDERS
        );

        return response()->json($folders);
    }

    public function get_recent_messages(Request $request)
    {
        if ($auth = PermissionService::authorize('mail', 'show')) return $auth;

        $folderPath = $request->query('folder', 'INBOX');
        if (!array_key_exists($folderPath, self::FOLDERS)) {
            return response()->json(['error' => 'Unknown folder'], 422);
        }

        try {
            $client = Client::account('default');
            $client->connect();

            $folder = $client->getFolder($folderPath);
            $messages = $folder->query()->all()->setFetchOrderDesc()->limit(20)->leaveUnread()->get();

            $result = $messages->map(function ($message) {
                $from = $message->getFrom();
                $firstFrom = $from ? $from->first() : null;
                return [
                    'id'      => $message->getUid(),
                    'subject' => (string) $message->getSubject(),
                    'from'    => $firstFrom ? ($firstFrom->personal ?: $firstFrom->mail) : '',
                    'date'    => $message->getDate() ? (string) $message->getDate() : null,
                    'seen'    => $message->getFlags()->has('seen'),
                ];
            })->values();

            $client->disconnect();

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'error'  => 'Cannot connect to mail server',
                'detail' => $e->getMessage(),
            ], 500);
        }
    }

    public function get_message(Request $request, $uid)
    {
        if ($auth = PermissionService::authorize('mail', 'show')) return $auth;

        $folderPath = $request->query('folder', 'INBOX');
        if (!array_key_exists($folderPath, self::FOLDERS)) {
            return response()->json(['error' => 'Unknown folder'], 422);
        }

        try {
            $client = Client::account('default');
            $client->connect();

            $folder = $client->getFolder($folderPath);
            $message = $folder->query()->getMessageByUid((int) $uid);

            $from = $message->getFrom();
            $firstFrom = $from ? $from->first() : null;
            $to = $message->getTo();

            $body = $message->getHTMLBody();
            if (!$body) {
                $text = $message->getTextBody();
                $body = $text ? nl2br(e($text)) : '';
            }

            $result = [
                'id'      => $message->getUid(),
                'subject' => (string) $message->getSubject(),
                'from'    => $firstFrom ? ($firstFrom->personal ? "{$firstFrom->personal} <{$firstFrom->mail}>" : $firstFrom->mail) : '',
                'to'      => $to ? implode(', ', $to->map(fn ($a) => $a->mail)) : '',
                'date'    => $message->getDate() ? (string) $message->getDate() : null,
                'body'    => $body,
            ];

            $client->disconnect();

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'error'  => 'Cannot load message',
                'detail' => $e->getMessage(),
            ], 500);
        }
    }
}
