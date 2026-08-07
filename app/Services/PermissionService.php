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
            return false;
        }

        return auth()->user()->hasPermissionFor($subject, $action);
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

        // return null; // Continue if allowed
    }

    /**
     * Like authorize(), but allows through if the user has ANY of the given
     * [subject, action] pairs — for endpoints reachable via more than one
     * distinct permission (e.g. a warehouse-restricted seller acting through
     * their own 'warehouse'/'sell_own' permission instead of the normal
     * 'order'/'add').
     *
     * @param array $pairs [[subject, action], ...]
     * @return \Illuminate\Http\JsonResponse|null
     */
    public static function authorizeAny(array $pairs)
    {
        foreach ($pairs as [$subject, $action]) {
            if (self::checkPermission($subject, $action)) {
                return null;
            }
        }

        return response()->json(['error' => 'Unauthorized'], 403);
    }
}
