<?php

namespace App\Services;

use App\Models\User;
use App\Models\User\ContentNotificationLog;
use App\Jobs\UserNotifications;

use App\Services\Notifications\ContentResolverInterface;
use App\Services\Notifications\Resolvers\ArticleResolver;
use App\Services\Notifications\Resolvers\SectorResolver;
use App\Services\Notifications\Resolvers\MountResolver;
use App\Services\Notifications\Resolvers\ProductResolver;
use App\Services\Notifications\Resolvers\ServiceResolver;
use App\Services\Notifications\Resolvers\TourResolver;
use App\Services\Notifications\Resolvers\PostResolver;
use App\Services\Notifications\Resolvers\SummitResolver;
use App\Services\Notifications\Resolvers\FilmResolver;

use App\Models\Guide\Article;
use App\Models\Guide\Sector;
use App\Models\Guide\Mount;
use App\Models\Shop\Product;
use App\Models\Shop\Service;
use App\Models\Shop\Tour;
use App\Models\Blog\Post;
use App\Models\Summit\Summit;
use App\Models\Films\Film;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class NotificationDispatchService
{
    private const RESOLVERS = [
        Article::class => ArticleResolver::class,
        Sector::class  => SectorResolver::class,
        Mount::class   => MountResolver::class,
        Product::class => ProductResolver::class,
        Service::class => ServiceResolver::class,
        Tour::class    => TourResolver::class,
        Post::class    => PostResolver::class,
        Summit::class  => SummitResolver::class,
        Film::class    => FilmResolver::class,
    ];

    public static function resolverFor(string $modelClass): ?ContentResolverInterface
    {
        $resolverClass = self::RESOLVERS[$modelClass] ?? null;
        return $resolverClass ? new $resolverClass() : null;
    }

    public static function usersWithPreference(string $preferenceKey): Collection
    {
        return User::whereHas('notification_list', function ($q) use ($preferenceKey) {
            $q->where('notification_type->' . $preferenceKey, true);
        })->get();
    }

    /**
     * $bypassDedup: still logs (best-effort, insertOrIgnore) for analytics, but always
     * dispatches the email regardless of whether a row already existed for this
     * (user, item, key). Used for the admin's explicit "notify subscribers about
     * this update" checkbox — a deliberate per-save action should always go out,
     * unlike the standard once-ever-per-item guard.
     */
    public static function sendOnce(User $user, Model $notifiable, string $notificationKey, string $url, string $subject, string $text, bool $bypassDedup = false): bool
    {
        $inserted = ContentNotificationLog::insertOrIgnore([[
            'user_id' => $user->id,
            'notifiable_type' => get_class($notifiable),
            'notifiable_id' => $notifiable->id,
            'notification_key' => $notificationKey,
            'sent_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]]);

        if ($inserted === 0 && !$bypassDedup) {
            return false;
        }

        UserNotifications::dispatch($url, $text, $subject, $user->email)->onQueue('emails');

        return true;
    }

    /**
     * $force: bypasses the once-ever-per-item dedup (see sendOnce()). Automatic calls
     * (the draft→published transition) leave this false — nothing to dedupe against
     * yet. An admin explicitly choosing "notify as new content" on an already-published
     * item's edit form passes true, to deliberately re-announce it even to users who
     * were already sent the original new_content email.
     */
    public static function notifyNewContent(string $modelClass, int $id, bool $force = false): void
    {
        $resolver = self::resolverFor($modelClass);
        if (!$resolver) {
            return;
        }

        $model = $modelClass::find($id);
        if (!$model) {
            return;
        }

        $preferenceKey = $resolver->preferenceKey($model);
        if ($preferenceKey === null) {
            return;
        }

        foreach (self::usersWithPreference($preferenceKey) as $user) {
            $locale = $user->lang === 'ka' ? 'ka' : 'us';
            $title = $resolver->title($model, $locale);

            self::sendOnce(
                $user,
                $model,
                'new_content',
                $resolver->url($model),
                $title,
                self::buildNewContentText($title, $locale),
                $force
            );
        }

        if ($resolver->favoriteModelClass()) {
            self::notifyFavoritersOfPublish($modelClass, $id, $force);
        }
    }

    public static function notifyFavoritersOfPublish(string $modelClass, int $id, bool $force = false): void
    {
        $resolver = self::resolverFor($modelClass);
        if (!$resolver || !$resolver->favoriteModelClass()) {
            return;
        }

        $model = $modelClass::find($id);
        if (!$model) {
            return;
        }

        $favoriteModelClass = $resolver->favoriteModelClass();
        $favoriteForeignKey = $resolver->favoriteForeignKey();

        $favorites = $favoriteModelClass::where($favoriteForeignKey, $id)->get();

        foreach ($favorites as $favorite) {
            $user = User::find($favorite->user_id);
            if (!$user) {
                continue;
            }

            $locale = $user->lang === 'ka' ? 'ka' : 'us';
            $title = $resolver->title($model, $locale);

            self::sendOnce(
                $user,
                $model,
                'favorite_publish',
                $resolver->url($model),
                $title,
                self::buildFavoriteText($title, $locale),
                $force
            );
        }
    }

    /**
     * $force: bypasses the once-ever-per-item dedup (see sendOnce()). This is the only
     * path that ever fires an update notification — it's driven exclusively by the
     * admin's explicit "notify subscribers about this update" checkbox on the edit
     * form, so $force should always be true in practice; there is no automatic/silent
     * trigger for edits to already-published content.
     */
    public static function notifyContentUpdated(string $modelClass, int $id, bool $force = false): void
    {
        $resolver = self::resolverFor($modelClass);
        if (!$resolver) {
            return;
        }

        $model = $modelClass::find($id);
        if (!$model) {
            return;
        }

        $preferenceKey = $resolver->updatePreferenceKey($model);
        if ($preferenceKey === null) {
            return;
        }

        foreach (self::usersWithPreference($preferenceKey) as $user) {
            $locale = $user->lang === 'ka' ? 'ka' : 'us';
            $title = $resolver->title($model, $locale);

            self::sendOnce(
                $user,
                $model,
                'content_updated',
                $resolver->url($model),
                $title,
                self::buildUpdatedContentText($title, $locale),
                $force
            );
        }

        if ($resolver->favoriteModelClass()) {
            self::notifyFavoritersOfUpdate($modelClass, $id, $force);
        }
    }

    public static function notifyFavoritersOfUpdate(string $modelClass, int $id, bool $force = false): void
    {
        $resolver = self::resolverFor($modelClass);
        if (!$resolver || !$resolver->favoriteModelClass()) {
            return;
        }

        $model = $modelClass::find($id);
        if (!$model) {
            return;
        }

        $favoriteModelClass = $resolver->favoriteModelClass();
        $favoriteForeignKey = $resolver->favoriteForeignKey();

        $favorites = $favoriteModelClass::where($favoriteForeignKey, $id)->get();

        foreach ($favorites as $favorite) {
            $user = User::find($favorite->user_id);
            if (!$user) {
                continue;
            }

            $locale = $user->lang === 'ka' ? 'ka' : 'us';
            $title = $resolver->title($model, $locale);

            self::sendOnce(
                $user,
                $model,
                'favorite_updated',
                $resolver->url($model),
                $title,
                self::buildFavoriteUpdatedText($title, $locale),
                $force
            );
        }
    }

    private static function buildNewContentText(string $title, string $locale): string
    {
        if ($locale === 'ka') {
            return 'ახალი კონტენტი დაემატა climbing.ge-ზე: (' . $title . '). ეწვიეთ გვერდს მეტის გასაგებად.';
        }

        return 'New content was just added on climbing.ge: (' . $title . '). Visit the page to find out more.';
    }

    private static function buildFavoriteText(string $title, string $locale): string
    {
        if ($locale === 'ka') {
            return 'თქვენი რჩეული გვერდი განახლდა climbing.ge-ზე: (' . $title . '). ეწვიეთ გვერდს სიახლის სანახავად.';
        }

        return 'One of your favorites was just updated on climbing.ge: (' . $title . '). Visit the page to see what is new.';
    }

    private static function buildUpdatedContentText(string $title, string $locale): string
    {
        if ($locale === 'ka') {
            return 'კონტენტი განახლდა climbing.ge-ზე: (' . $title . '). ეწვიეთ გვერდს ცვლილებების სანახავად.';
        }

        return 'Content was just updated on climbing.ge: (' . $title . '). Visit the page to see what changed.';
    }

    private static function buildFavoriteUpdatedText(string $title, string $locale): string
    {
        if ($locale === 'ka') {
            return 'თქვენი რჩეული გვერდი შეიცვალა climbing.ge-ზე: (' . $title . '). ეწვიეთ გვერდს ცვლილებების სანახავად.';
        }

        return 'One of your favorites was just edited on climbing.ge: (' . $title . '). Visit the page to see what changed.';
    }
}
