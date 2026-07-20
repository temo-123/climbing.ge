<?php

namespace App\Observers;

use App\Models\Guide\Mount;
use App\Services\NotificationDispatchService;

class MountObserver
{
    public function created(Mount $mount): void
    {
        // The `published` column on `mounts` is never set by the admin controller,
        // so mounts are always treated as live as soon as they exist.
        NotificationDispatchService::notifyNewContent(Mount::class, $mount->id);
    }

    public function updated(Mount $mount): void
    {
        // Same reasoning as created(): mounts have no draft state, so every
        // edit after creation is an update to already-live content — but it
        // only actually sends when the admin opted in via notifyMode.
        if ($mount->notifyMode === 'update') {
            NotificationDispatchService::notifyContentUpdated(Mount::class, $mount->id, true);
        } elseif ($mount->notifyMode === 'new') {
            NotificationDispatchService::notifyNewContent(Mount::class, $mount->id, true);
        }
    }
}
