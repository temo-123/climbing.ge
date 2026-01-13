<?php

namespace App\Services;

use Illuminate\Http\Response;

class PermissionService
{
    /**
     * Check if the authenticated user has permission for the given subject and action.
     *
     * @param string $subject
     * @param string $action
     * @return bool
     */
    public static function checkPermission($subject, $action)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // return auth()->user()->hasPermissionFor($subject, $action);
    }

    /**
     * Check permission and return unauthorized response if not allowed.
     *
     * @param string $subject
     * @param string $action
     * @return \Illuminate\Http\JsonResponse|null
     */
    public static function authorize($subject, $action)
    {
        if (!self::checkPermission($subject, $action)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return null; // Continue if allowed
    }
}
