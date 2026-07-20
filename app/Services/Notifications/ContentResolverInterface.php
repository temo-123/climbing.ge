<?php

namespace App\Services\Notifications;

use Illuminate\Database\Eloquent\Model;

interface ContentResolverInterface
{
    /** Preference key subscribers must have enabled, or null if this item is out of scope for auto-notification. */
    public function preferenceKey(Model $model): ?string;

    /** Preference key subscribers must have enabled to hear about edits to already-published items, or null if out of scope. */
    public function updatePreferenceKey(Model $model): ?string;

    public function title(Model $model, string $locale): string;

    public function url(Model $model): string;

    /** Favorite model class for users who favorited this specific item, or null if not favoritable. */
    public function favoriteModelClass(): ?string;

    /** Foreign key column on the favorite model pointing back at this item's id. */
    public function favoriteForeignKey(): ?string;
}
