<?php

namespace App\Http\Controllers\Api\User\Admin\User;

use App\Http\Controllers\Controller;
use App\Services\AdminAlertsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminAlertsController extends Controller
{
    public function summary(AdminAlertsService $service): JsonResponse
    {
        return response()->json($service->getAlerts(auth()->user()));
    }

    public function mark_viewed(Request $request, AdminAlertsService $service): JsonResponse
    {
        $request->validate(['key' => 'required|string']);

        $service->markViewed(auth()->user(), $request->input('key'));

        return response()->json(['success' => true]);
    }
}
