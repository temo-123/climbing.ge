<?php

namespace App\Http\Controllers\Api\User\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use App\Services\PermissionService;

class MultimediaController extends Controller
{
    protected $basePath;

    public function __construct()
    {
        $this->basePath = public_path('images');
    }

    /**
     * Get the folder structure with used/unused flag for each image.
     */
    public function getImagesFolderStructure(Request $request): JsonResponse
    {
        if ($auth = PermissionService::authorize('export', 'show')) return $auth;

        $search     = $request->get('search', '');
        $sortBy     = $request->get('sort_by', 'name');
        $filterType = $request->get('filter_type', '');

        $usedFilenames = $this->collectUsedImageFilenames();

        $structure = $this->getDirectoryStructure(
            $this->basePath, '', $search, $sortBy, $filterType, $usedFilenames
        );

        return response()->json($structure);
    }

    /**
     * Tables that hold framework/queue bookkeeping data rather than site
     * content — never worth scanning for image references.
     */
    private const NON_CONTENT_TABLES = [
        'migrations', 'jobs', 'failed_jobs', 'job_batches',
        'password_resets', 'password_reset_tokens', 'personal_access_tokens',
        'sessions', 'cache', 'cache_locks',
    ];

    /**
     * Collect every image filename referenced anywhere in the database.
     *
     * Instead of a hand-maintained table/column list (which silently misses
     * new image fields — e.g. products, brands, mtp routes, site settings —
     * and then wrongly flags their images as "unused"), this inspects the
     * live schema for any column that plausibly stores an image reference:
     *   1. varchar/char columns named like image/photo/avatar/logo/... (direct file refs)
     *   2. text/longtext/json columns (rich-text/JSON bodies) scanned for
     *      embedded filenames with an image extension.
     *
     * Returns a flat Set-like array (values are keys for O(1) lookup).
     */
    private function collectUsedImageFilenames(): array
    {
        $used     = [];
        $database = DB::getDatabaseName();

        $imageColumns = DB::select("
            SELECT TABLE_NAME, COLUMN_NAME FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = ?
              AND DATA_TYPE IN ('varchar', 'char')
              AND (
                    COLUMN_NAME LIKE '%image%' OR COLUMN_NAME LIKE '%photo%' OR COLUMN_NAME LIKE '%avatar%'
                 OR COLUMN_NAME LIKE '%logo%'  OR COLUMN_NAME LIKE '%icon%'  OR COLUMN_NAME LIKE '%thumbnail%'
                 OR COLUMN_NAME LIKE '%cover%' OR COLUMN_NAME LIKE '%gallery%' OR COLUMN_NAME LIKE '%banner%'
                 OR COLUMN_NAME LIKE '%picture%'
              )
        ", [$database]);

        foreach ($imageColumns as $col) {
            if (in_array($col->TABLE_NAME, self::NON_CONTENT_TABLES)) continue;

            try {
                $rows = DB::table($col->TABLE_NAME)->whereNotNull($col->COLUMN_NAME)->pluck($col->COLUMN_NAME);
                foreach ($rows as $value) {
                    if ($value) $used[basename(parse_url($value, PHP_URL_PATH) ?: $value)] = true;
                }
            } catch (\Exception $e) {
                // column readable via information_schema but not queryable (view, permissions, etc.)
            }
        }

        // Scan rich-text / JSON bodies for embedded image filenames
        // (editor HTML like <img src="...">, CSS background-image, JSON blobs, ...)
        $textColumns = DB::select("
            SELECT TABLE_NAME, COLUMN_NAME FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = ?
              AND DATA_TYPE IN ('text', 'mediumtext', 'longtext', 'json')
        ", [$database]);

        foreach ($textColumns as $col) {
            if (in_array($col->TABLE_NAME, self::NON_CONTENT_TABLES)) continue;

            try {
                $rows = DB::table($col->TABLE_NAME)->whereNotNull($col->COLUMN_NAME)->pluck($col->COLUMN_NAME);
                foreach ($rows as $content) {
                    if (!$content) continue;
                    preg_match_all('/[\w\-.\/%]+\.(?:jpe?g|png|gif|webp|svg)\b/i', $content, $m);
                    foreach ($m[0] as $match) {
                        $used[basename($match)] = true;
                    }
                }
            } catch (\Exception $e) {}
        }

        return $used;
    }

    /**
     * Upload images
     */
    public function uploadImages(Request $request): JsonResponse
    {
        if ($auth = PermissionService::authorize('export', 'show')) return $auth;

        $validator = Validator::make($request->all(), [
            'files.*' => 'required|file|image|mimes:jpeg,jpg,png,gif,webp,svg|max:10240',
            'folder'  => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $uploadedFiles = [];
            $folder        = $request->get('folder', '');
            $targetPath    = $folder ? $this->basePath . '/' . $folder : $this->basePath;

            if (!File::exists($targetPath)) {
                File::makeDirectory($targetPath, 0755, true);
            }

            foreach ($request->file('files') as $file) {
                $fileName = $this->generateUniqueFileName($file);
                $file->move($targetPath, $fileName);
                $filePath   = $targetPath . '/' . $fileName;
                $metadata   = $this->getFileMetadataFromPath($filePath);

                $uploadedFiles[] = [
                    'name'       => $fileName,
                    'path'       => $folder ? $folder . '/' . $fileName : $fileName,
                    'url'        => url('images/' . ($folder ? $folder . '/' . $fileName : $fileName)),
                    'type'       => $file->getClientOriginalExtension(),
                    'size'       => $metadata['size'],
                    'dimensions' => $metadata['dimensions'],
                    'modified'   => $metadata['modified'],
                    'used'       => false,
                ];
            }

            return response()->json(['success' => true, 'files' => $uploadedFiles, 'message' => 'Files uploaded successfully']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Upload failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Delete files or folders
     */
    public function deleteItems(Request $request): JsonResponse
    {
        if ($auth = PermissionService::authorize('export', 'show')) return $auth;

        $validator = Validator::make($request->all(), [
            'paths'   => 'required|array',
            'paths.*' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $deletedItems = [];
            $failedItems  = [];

            foreach ($request->paths as $path) {
                $fullPath = $this->basePath . '/' . $path;
                if (File::exists($fullPath)) {
                    File::isDirectory($fullPath)
                        ? File::deleteDirectory($fullPath)
                        : File::delete($fullPath);
                    $deletedItems[] = $path;
                } else {
                    $failedItems[] = $path;
                }
            }

            return response()->json(['success' => true, 'deleted' => $deletedItems, 'failed' => $failedItems]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Delete failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Create a new folder
     */
    public function createFolder(Request $request): JsonResponse
    {
        if ($auth = PermissionService::authorize('export', 'show')) return $auth;

        $validator = Validator::make($request->all(), [
            'name'   => 'required|string|max:255|regex:/^[a-zA-Z0-9\-_\s]+$/',
            'parent' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $folderName = Str::slug($request->name);
            $parentPath = $request->get('parent', '');
            $fullPath   = $parentPath
                ? $this->basePath . '/' . $parentPath . '/' . $folderName
                : $this->basePath . '/' . $folderName;

            if (File::exists($fullPath)) {
                return response()->json(['success' => false, 'message' => 'Folder already exists'], 422);
            }

            File::makeDirectory($fullPath, 0755, true);

            return response()->json([
                'success' => true,
                'folder'  => ['name' => $folderName, 'path' => $parentPath ? $parentPath . '/' . $folderName : $folderName, 'type' => 'directory'],
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Folder creation failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get enhanced directory structure with used/unused flag per image.
     */
    private function getDirectoryStructure($path, $relativePath = '', $search = '', $sortBy = 'name', $filterType = '', array $usedFilenames = [])
    {
        $structure = [];

        if (!File::exists($path)) {
            return $structure;
        }

        foreach (File::directories($path) as $dir) {
            $dirName    = basename($dir);
            $dirRelative = $relativePath ? $relativePath . '/' . $dirName : $dirName;

            if ($search && !str_contains(strtolower($dirName), strtolower($search))) {
                continue;
            }

            $structure[] = [
                'type'     => 'directory',
                'name'     => $dirName,
                'path'     => $dirRelative,
                'size'     => $this->getFolderSize($dir),
                'modified' => filemtime($dir),
                'children' => $this->getDirectoryStructure($dir, $dirRelative, $search, $sortBy, $filterType, $usedFilenames),
            ];
        }

        foreach (File::files($path) as $file) {
            $fileName    = $file->getFilename();
            $fileRelative = $relativePath ? $relativePath . '/' . $fileName : $fileName;

            if ($search && !str_contains(strtolower($fileName), strtolower($search))) {
                continue;
            }

            $extension = strtolower($file->getExtension());
            if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
                continue;
            }

            $metadata  = $this->getFileMetadataFromPath($file->getPathname());
            $imageUrl  = $this->generateImageUrl($fileRelative);
            $isUsed    = isset($usedFilenames[basename($fileName)]);

            $fileData = [
                'type'       => 'file',
                'name'       => $fileName,
                'path'       => $fileRelative,
                'url'        => $imageUrl,
                'size'       => $metadata['size'],
                'extension'  => $extension,
                'dimensions' => $metadata['dimensions'],
                'modified'   => $metadata['modified'],
                'used'       => $isUsed,
            ];

            if (!$filterType || $extension === strtolower($filterType)) {
                $structure[] = $fileData;
            }
        }

        return $this->sortStructure($structure, $sortBy);
    }

    private function generateImageUrl($relativePath)
    {
        if (File::exists(public_path('images/' . $relativePath))) {
            return url('images/' . $relativePath);
        }
        return url('images/' . $relativePath);
    }

    private function getFileMetadataFromPath($filePath)
    {
        $metadata  = ['size' => filesize($filePath), 'modified' => filemtime($filePath), 'dimensions' => null];
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif']) && function_exists('getimagesize')) {
            $info = @getimagesize($filePath);
            if ($info) $metadata['dimensions'] = $info[0] . 'x' . $info[1];
        }
        return $metadata;
    }

    private function generateUniqueFileName($file)
    {
        $extension = $file->getClientOriginalExtension();
        $name      = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        return $name . '_' . time() . '.' . $extension;
    }

    private function getFolderSize($folderPath)
    {
        $size = 0;
        foreach (File::allFiles($folderPath) as $file) {
            $size += $file->getSize();
        }
        return $size;
    }

    private function sortStructure($structure, $sortBy)
    {
        usort($structure, function ($a, $b) use ($sortBy) {
            switch ($sortBy) {
                case 'name': return strcmp($a['name'], $b['name']);
                case 'date': return ($b['modified'] ?? 0) - ($a['modified'] ?? 0);
                case 'size': return ($b['size'] ?? 0) - ($a['size'] ?? 0);
                default:     return 0;
            }
        });
        return $structure;
    }
}
