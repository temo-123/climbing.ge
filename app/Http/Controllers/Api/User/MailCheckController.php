<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Webklex\IMAP\Facades\Client;

class MailCheckController extends Controller
{
    public function get_unread_count(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->role()->where('name', 'admin')->exists()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

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
}
