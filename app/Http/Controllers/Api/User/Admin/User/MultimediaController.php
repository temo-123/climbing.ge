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
     * Collect every image filename referenced in the database.
     * Returns a flat Set-like array (values are keys for O(1) lookup).
     */
    private function collectUsedImageFilenames(): array
    {
        $used = [];

        // Tables with a simple `image` column
        $simpleTables = [
            'articles', 'posts', 'summits', 'header_images',
            'sector_local_images', 'spot_rocks_images',
            'mount_route_images', 'service_images', 'tour_images',
            'article_images', 'users',
        ];

        foreach ($simpleTables as $table) {
            try {
                $rows = DB::table($table)->whereNotNull('image')->pluck('image');
                foreach ($rows as $img) {
                    if ($img) $used[basename($img)] = true;
                }
            } catch (\Exception $e) {
                // table might not exist in some environments
            }
        }

        // Scan editor HTML content for embedded <img src="..."> filenames
        $textColumns = [
            ['table' => 'locale_articles', 'col' => 'text'],
            ['table' => 'locale_posts',    'col' => 'text'],
        ];
        foreach ($textColumns as $tc) {
            try {
                $rows = DB::table($tc['table'])->whereNotNull($tc['col'])->pluck($tc['col']);
                foreach ($rows as $html) {
                    preg_match_all('/src=["\']([^"\']+)["\']/', $html ?? '', $m);
                    foreach ($m[1] as $src) {
                        $used[basename($src)] = true;
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
