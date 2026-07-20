<?php

namespace App\Observers;

use App\Models\Summit\Summit;
use App\Services\NotificationDispatchService;

class SummitObserver
{
    public function created(Summit $summit): void
    {
        if ((int)($summit->published ?? 0) === 1) {
            NotificationDispatchService::notifyNewContent(Summit::class, $summit->id);
        }
    }

    public function updated(Summit $summit): void
    {
        $wasLive = (int)$summit->getOriginal('published') === 1;
        $isLiveNow = (int)$summit->published === 1;

        if (!$wasLive && $isLiveNow) {
            NotificationDispatchService::notifyNewContent(Summit::class, $summit->id);
        } elseif ($wasLive && $isLiveNow && $summit->notifyMode === 'update') {
            NotificationDispatchService::notifyContentUpdated(Summit::class, $summit->id, true);
        } elseif ($wasLive && $isLiveNow && $summit->notifyMode === 'new') {
            NotificationDispatchService::notifyNewContent(Summit::class, $summit->id, true);
        }
    }
}
