<?php

namespace App\Observers;

use App\Models\Films\Film;
use App\Services\NotificationDispatchService;

class FilmObserver
{
    public function created(Film $film): void
    {
        if ((int)($film->published ?? 0) === 1) {
            NotificationDispatchService::notifyNewContent(Film::class, $film->id);
        }
    }

    public function updated(Film $film): void
    {
        $wasLive = (int)$film->getOriginal('published') === 1;
        $isLiveNow = (int)$film->published === 1;

        if (!$wasLive && $isLiveNow) {
            NotificationDispatchService::notifyNewContent(Film::class, $film->id);
        } elseif ($wasLive && $isLiveNow && $film->notifyMode === 'update') {
            NotificationDispatchService::notifyContentUpdated(Film::class, $film->id, true);
        } elseif ($wasLive && $isLiveNow && $film->notifyMode === 'new') {
            NotificationDispatchService::notifyNewContent(Film::class, $film->id, true);
        }
    }
}
