<?php

namespace App\Http\Controllers\Api\User\Admin\User;

use App\Http\Controllers\Controller;
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

    // -------------------------------------------------------------------------
    // Integrity checks
    // -------------------------------------------------------------------------

    private function detectIssues(): array
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
        return [
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
    }
}
