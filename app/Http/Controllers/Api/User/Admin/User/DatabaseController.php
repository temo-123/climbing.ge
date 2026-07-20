<?php

namespace App\Http\Controllers\Api\User\Admin\User;

use App\Http\Controllers\Controller;
use App\Services\PermissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DatabaseController extends Controller
{
    /**
     * Return all tables with row counts and detected integrity issues.
     */
    public function getTableStats(): JsonResponse
    {
        if ($auth = PermissionService::authorize('database', 'show')) return $auth;

        $dbName = DB::selectOne('SELECT DATABASE() as db')->db;

        $tables = DB::select("
            SELECT
                TABLE_NAME      AS name,
                TABLE_ROWS      AS row_count,
                DATA_LENGTH + INDEX_LENGTH AS size_bytes,
                ENGINE,
                TABLE_COLLATION AS collation
            FROM information_schema.TABLES
            WHERE TABLE_SCHEMA = ?
            ORDER BY TABLE_NAME
        ", [$dbName]);

        // Use exact counts for accuracy (TABLE_ROWS is an estimate for InnoDB)
        foreach ($tables as $t) {
            try {
                $t->row_count = DB::table($t->name)->count();
            } catch (\Exception $e) {
                $t->row_count = null;
            }
        }

        $issues = $this->detectIssues();

        return response()->json([
            'tables' => $tables,
            'issues' => $issues,
        ]);
    }

    /**
     * Fix a specific integrity issue by key.
     */
    public function fixIssue(Request $request): JsonResponse
    {
        if ($auth = PermissionService::authorize('database', 'edit')) return $auth;

        $key = $request->input('key');

        $fixes = $this->getFixMap();

        if (!isset($fixes[$key])) {
            return response()->json(['success' => false, 'message' => 'Unknown fix key'], 422);
        }

        try {
            $affected = $fixes[$key]();
            return response()->json(['success' => true, 'affected' => $affected, 'message' => "Fixed: {$affected} rows removed"]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Fix every currently-detected issue that has an automatic fix available.
     * Issues with no fix closure (e.g. missing-field checks on sectors/summits)
     * are left untouched and still show up in a subsequent detectIssues() call.
     */
    public function fixAllIssues(): JsonResponse
    {
        if ($auth = PermissionService::authorize('database', 'edit')) return $auth;

        $fixes = $this->getFixMap();
        $affected = 0;
        $fixedKeys = 0;

        foreach ($this->detectIssues() as $issue) {
            if (!$issue['fixable'] || !isset($fixes[$issue['key']])) {
                continue;
            }

            try {
                $affected += $fixes[$issue['key']]();
                $fixedKeys++;
            } catch (\Exception $e) {
                // skip this key, keep fixing the rest
            }
        }

        $remaining = count($this->detectIssues());

        return response()->json([
            'success'   => true,
            'affected'  => $affected,
            'fixed_keys' => $fixedKeys,
            'remaining' => $remaining,
        ]);
    }

    // -------------------------------------------------------------------------
    // Integrity checks
    // -------------------------------------------------------------------------

    /**
     * Public so other admin features (e.g. AdminAlertsService) can reuse the
     * same integrity checks without duplicating the queries.
     */
    public function detectIssues(): array
    {
        $checks = [
            // Orphaned article gallery images
            [
                'key'   => 'orphan_article_images',
                'table' => 'article_images',
                'label' => 'Gallery images with no parent article',
                'count' => fn() => DB::table('article_images')
                    ->whereNotIn('article_id', DB::table('articles')->pluck('id'))
                    ->count(),
            ],
            // Orphaned spot_rocks_images
            [
                'key'   => 'orphan_spot_rocks_images',
                'table' => 'spot_rocks_images',
                'label' => 'Spot rock images with no parent article',
                'count' => fn() => DB::table('spot_rocks_images')
                    ->whereNotIn('article_id', DB::table('articles')->pluck('id'))
                    ->count(),
            ],
            // Orphaned locale_articles (us side)
            [
                'key'   => 'orphan_locale_articles_us',
                'table' => 'locale_articles',
                'label' => 'EN locale articles linked to non-existent article',
                'count' => fn() => DB::table('articles')
                    ->whereNotNull('us_article_id')
                    ->whereNotIn('us_article_id', DB::table('locale_articles')->pluck('id'))
                    ->count(),
            ],
            // Orphaned locale_articles (ka side)
            [
                'key'   => 'orphan_locale_articles_ka',
                'table' => 'locale_articles',
                'label' => 'KA locale articles linked to non-existent article',
                'count' => fn() => DB::table('articles')
                    ->whereNotNull('ka_article_id')
                    ->whereNotIn('ka_article_id', DB::table('locale_articles')->pluck('id'))
                    ->count(),
            ],
            // Orphaned locale_posts (us side)
            [
                'key'   => 'orphan_locale_posts_us',
                'table' => 'locale_posts',
                'label' => 'EN locale posts linked to non-existent post',
                'count' => fn() => DB::table('posts')
                    ->whereNotNull('us_post_id')
                    ->whereNotIn('us_post_id', DB::table('locale_posts')->pluck('id'))
                    ->count(),
            ],
            // Articles with no locale data at all
            [
                'key'   => 'articles_no_locale',
                'table' => 'articles',
                'label' => 'Articles with neither EN nor KA locale data',
                'count' => fn() => DB::table('articles')
                    ->whereNull('us_article_id')
                    ->whereNull('ka_article_id')
                    ->count(),
            ],
            // Orders with no associated user
            [
                'key'   => 'orphan_orders',
                'table' => 'orders',
                'label' => 'Orders linked to non-existent user',
                'count' => fn() => DB::table('orders')
                    ->whereNotNull('user_id')
                    ->whereNotIn('user_id', DB::table('users')->pluck('id'))
                    ->count(),
            ],
            // Carts with no user
            [
                'key'   => 'orphan_carts',
                'table' => 'carts',
                'label' => 'Cart rows linked to non-existent user',
                'count' => fn() => DB::table('carts')
                    ->whereNotIn('user_id', DB::table('users')->pluck('id'))
                    ->count(),
            ],
        ];

        // Content types that store translations in a separate locale_X table,
        // linked via us_{singular}_id / ka_{singular}_id FK columns (same shape as
        // articles/posts above, just repeated per content type).
        $localeContentTypes = [
            'products' => ['locale_products', 'product'],
            'services' => ['locale_services', 'service'],
            'tours'    => ['locale_tours', 'tour'],
            'films'    => ['locale_films', 'film'],
            'mounts'   => ['locale_mounts', 'mount'],
        ];

        foreach ($localeContentTypes as $table => [$localeTable, $singular]) {
            foreach (['us', 'ka'] as $locale) {
                $col = "{$locale}_{$singular}_id";
                $checks[] = [
                    'key'   => "orphan_locale_{$table}_{$locale}",
                    'table' => $localeTable,
                    'label' => strtoupper($locale) . " locale {$table} linked to non-existent {$singular}",
                    'count' => fn() => DB::table($table)
                        ->whereNotNull($col)
                        ->whereNotIn($col, DB::table($localeTable)->pluck('id'))
                        ->count(),
                ];
            }

            $usCol = "us_{$singular}_id";
            $kaCol = "ka_{$singular}_id";
            $checks[] = [
                'key'   => "{$table}_no_locale",
                'table' => $table,
                'label' => ucfirst($table) . ' with neither EN nor KA locale data',
                'count' => fn() => DB::table($table)
                    ->whereNull($usCol)
                    ->whereNull($kaCol)
                    ->count(),
            ];
        }

        // "Leftover translation row" checks: a locale_X row whose parent
        // (articles/products/...) doesn't (or no longer does) point back at
        // it — e.g. the parent was deleted directly without also deleting
        // its two locale rows. This is the opposite direction from the
        // orphan_locale_* checks above (which catch a parent pointing at a
        // locale id that no longer exists) and is what the "article errors"
        // dashboard alert has always detected for articles; generalized
        // here to every entity that has the same locale-table shape.
        foreach ($this->translationEntities() as $table => [$localeTable, $singular, $displayColumn]) {
            foreach (['us', 'ka'] as $locale) {
                $col = "{$locale}_{$singular}_id";
                $checks[] = [
                    'key'   => "orphan_translation_{$table}_{$locale}",
                    'table' => $localeTable,
                    'label' => strtoupper($locale) . " {$table} translation rows with no parent {$singular}",
                    'count' => fn() => DB::table($localeTable)
                        ->where('locale', $locale)
                        ->whereNotIn('id', DB::table($table)->whereNotNull($col)->pluck($col))
                        ->count(),
                ];
            }
        }

        $issues = [];
        foreach ($checks as $check) {
            try {
                $count = ($check['count'])();
                if ($count > 0) {
                    $issues[] = [
                        'key'       => $check['key'],
                        'table'     => $check['table'],
                        'label'     => $check['label'],
                        'count'     => $count,
                        'fixable'   => isset($this->getFixMap()[$check['key']]),
                    ];
                }
            } catch (\Exception $e) {
                // table may not exist
            }
        }

        return $issues;
    }

    /**
     * Map of fixable issue keys → closures that delete the bad rows and return affected count.
     */
    private function getFixMap(): array
    {
        $fixes = [
            'orphan_article_images' => function () {
                return DB::table('article_images')
                    ->whereNotIn('article_id', DB::table('articles')->pluck('id'))
                    ->delete();
            },
            'orphan_spot_rocks_images' => function () {
                return DB::table('spot_rocks_images')
                    ->whereNotIn('article_id', DB::table('articles')->pluck('id'))
                    ->delete();
            },
            'orphan_orders' => function () {
                return DB::table('orders')
                    ->whereNotNull('user_id')
                    ->whereNotIn('user_id', DB::table('users')->pluck('id'))
                    ->delete();
            },
            'orphan_carts' => function () {
                return DB::table('carts')
                    ->whereNotIn('user_id', DB::table('users')->pluck('id'))
                    ->delete();
            },
        ];

        foreach ($this->translationEntities() as $table => [$localeTable, $singular, $displayColumn]) {
            foreach (['us', 'ka'] as $locale) {
                $col = "{$locale}_{$singular}_id";
                $fixes["orphan_translation_{$table}_{$locale}"] = function () use ($localeTable, $table, $col, $locale) {
                    return DB::table($localeTable)
                        ->where('locale', $locale)
                        ->whereNotIn('id', DB::table($table)->whereNotNull($col)->pluck($col))
                        ->delete();
                };
            }
        }

        return $fixes;
    }

    /**
     * Preview rows for a fixable/viewable "leftover translation row" issue key
     * (orphan_translation_{table}_{locale}) — generalizes the old
     * SiteDataController::get_article_errors to every entity.
     */
    public function getIssueRows(Request $request, string $key): JsonResponse
    {
        if ($auth = PermissionService::authorize('database', 'show')) return $auth;

        if (!preg_match('/^orphan_translation_(.+)_(us|ka)$/', $key, $m)) {
            return response()->json([], 422);
        }

        [, $table, $locale] = $m;
        $entities = $this->translationEntities();

        if (!isset($entities[$table])) {
            return response()->json([], 422);
        }

        [$localeTable, $singular, $displayColumn] = $entities[$table];
        $col = "{$locale}_{$singular}_id";

        $rows = DB::table($localeTable)
            ->where('locale', $locale)
            ->whereNotIn('id', DB::table($table)->whereNotNull($col)->pluck($col))
            ->get(['id', $displayColumn])
            ->map(fn ($row) => [
                'id'     => $row->id,
                'title'  => $row->{$displayColumn} ?: '(no title)',
                'locale' => $locale,
            ]);

        return response()->json($rows);
    }

    /**
     * Entities that store translations in a separate locale_X table, linked
     * via us_{singular}_id / ka_{singular}_id FK columns on the parent table.
     * table => [locale table, FK singular, display column on the locale table]
     */
    private function translationEntities(): array
    {
        return [
            'articles' => ['locale_articles', 'article', 'title'],
            'products' => ['locale_products', 'product', 'title'],
            'services' => ['locale_services', 'service', 'title'],
            'tours'    => ['locale_tours', 'tour', 'title'],
            'films'    => ['locale_films', 'film', 'name'],
            'mounts'   => ['locale_mounts', 'mount', 'title'],
            'posts'    => ['locale_posts', 'post', 'title'],
        ];
    }
}
