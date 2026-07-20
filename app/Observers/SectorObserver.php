<?php

namespace App\Observers;

use App\Models\Guide\Sector;
use App\Services\NotificationDispatchService;

class SectorObserver
{
    public function created(Sector $sector): void
    {
        if ((int)($sector->published ?? 0) === 1) {
            NotificationDispatchService::notifyNewContent(Sector::class, $sector->id);
        }
    }

    public function updated(Sector $sector): void
    {
        $wasLive = (int)$sector->getOriginal('published') === 1;
        $isLiveNow = (int)$sector->published === 1;

        if (!$wasLive && $isLiveNow) {
            NotificationDispatchService::notifyNewContent(Sector::class, $sector->id);
        } elseif ($wasLive && $isLiveNow && $sector->notifyMode === 'update') {
            NotificationDispatchService::notifyContentUpdated(Sector::class, $sector->id, true);
        } elseif ($wasLive && $isLiveNow && $sector->notifyMode === 'new') {
            NotificationDispatchService::notifyNewContent(Sector::class, $sector->id, true);
        }
    }
}
