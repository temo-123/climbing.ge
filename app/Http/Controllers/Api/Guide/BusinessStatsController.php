<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Guide\Suport_local_bisnes;
use App\Services\BusinessStatsService;

class BusinessStatsController extends Controller
{
    /**
     * Public beacon from the guide: a recommended service card was shown
     * (impression), opened (view), or a link / photo inside it was used.
     */
    public function track(Request $request, $bisnes_id)
    {
        $request->validate([
            'metric'     => 'required|in:' . implode(',', BusinessStatsService::METRICS),
            'article_id' => 'nullable|integer',
        ]);

        if (!Suport_local_bisnes::where('id', $bisnes_id)->exists()) {
            return response()->json(['ok' => false], 404);
        }

        BusinessStatsService::track((int) $bisnes_id, $request->metric, $request->article_id ? (int) $request->article_id : null, (string) $request->ip(), (string) $request->userAgent());

        return response()->json(['ok' => true]);
    }
}
