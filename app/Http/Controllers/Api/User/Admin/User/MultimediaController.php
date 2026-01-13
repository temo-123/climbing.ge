<?php

namespace App\Http\Controllers\Api\User\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MultimediaController extends Controller
{
    protected $basePath;

    public function __construct()
    {
        $this->basePath = public_path('images');
        
        // Debug: Check if base path exists
        if (!File::exists($this->basePath)) {
            \Log::warning('Images directory not found at: ' . $this->basePath);
        }
    }

    /**
     * Get the folder structure with enhanced metadata
     */
    public function getImagesFolderStructure(Request $request): JsonResponse
    {
        $search = $request->get('search', '');
        $sortBy = $request->get('sort_by', 'name');
        $filterType = $request->get('filter_type', '');
        
        $structure = $this->getDirectoryStructure($this->basePath, '', $search, $sortBy, $filterType);

        return response()->json($structure);
    }

    /**
     * Upload images
     */
    public function uploadImages(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'files.*' => 'required|file|image|mimes:jpeg,jpg,png,gif,webp,svg|max:10240', // 10MB max
            'folder' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $uploadedFiles = [];
            $folder = $request->get('folder', '');
            $targetPath = $folder ? $this->basePath . '/' . $folder : $this->basePath;

            // Ensure target directory exists
            if (!File::exists($targetPath)) {
                File::makeDirectory($targetPath, 0755, true);
            }

            foreach ($request->file('files') as $file) {
                $fileName = $this->generateUniqueFileName($file);
                $filePath = $targetPath . '/' . $fileName;
                
                // Move uploaded file
                $file->move($targetPath, $fileName);
                
                // Get file metadata
                $metadata = $this->getMetadataForUploadedFile($filePath);
                
                $uploadedFiles[] = [
                    'name' => $fileName,
                    'path' => $folder ? $folder . '/' . $fileName : $fileName,
                    'url' => url('images/' . ($folder ? $folder . '/' . $fileName : $fileName)),
                    'type' => $file->getClientOriginalExtension(),
                    'size' => $metadata['size'],
                    'dimensions' => $metadata['dimensions'],
                    'modified' => $metadata['modified']
                ];
            }

            return response()->json([
                'success' => true,
                'files' => $uploadedFiles,
                'message' => 'Files uploaded successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete files or folders
     */
    public function deleteItems(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'paths' => 'required|array',
            'paths.*' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $deletedItems = [];
            $failedItems = [];

            foreach ($request->paths as $path) {
                $fullPath = $this->basePath . '/' . $path;
                
                if (File::exists($fullPath)) {
                    if (File::isDirectory($fullPath)) {
                        // Remove directory and all contents
                        File::deleteDirectory($fullPath);
                        $deletedItems[] = $path;
                    } else {
                        // Remove file
                        File::delete($fullPath);
                        $deletedItems[] = $path;
                    }
                } else {
                    $failedItems[] = $path;
                }
            }

            return response()->json([
                'success' => true,
                'deleted' => $deletedItems,
                'failed' => $failedItems,
                'message' => 'Items deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Delete failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new folder
     */
    public function createFolder(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|regex:/^[a-zA-Z0-9\-_\s]+$/',
            'parent' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $folderName = Str::slug($request->name);
            $parentPath = $request->get('parent', '');
            $fullPath = $parentPath 
                ? $this->basePath . '/' . $parentPath . '/' . $folderName
                : $this->basePath . '/' . $folderName;

            if (File::exists($fullPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Folder already exists'
                ], 422);
            }

            File::makeDirectory($fullPath, 0755, true);

            return response()->json([
                'success' => true,
                'folder' => [
                    'name' => $folderName,
                    'path' => $parentPath ? $parentPath . '/' . $folderName : $folderName,
                    'type' => 'directory'
                ],
                'message' => 'Folder created successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Folder creation failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get file metadata
     */
    public function getFileMetadata(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'path' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $fullPath = $this->basePath . '/' . $request->path;
            
            if (!File::exists($fullPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File not found'
                ], 404);
            }

            $metadata = $this->getFileMetadataForPath($fullPath);

            return response()->json([
                'success' => true,
                'metadata' => $metadata
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get metadata: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search files and folders
     */
    public function search(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'query' => 'required|string|min:1|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $query = $request->query;
            $results = $this->searchInDirectory($this->basePath, $query);

            return response()->json([
                'success' => true,
                'results' => $results,
                'query' => $query
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Search failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get enhanced directory structure
     */
    private function getDirectoryStructure($path, $relativePath = '', $search = '', $sortBy = 'name', $filterType = '')
    {
        $structure = [];

        if (!File::exists($path)) {
            return $structure;
        }

        // Get directories first
        $directories = File::directories($path);
        foreach ($directories as $dir) {
            $dirName = basename($dir);
            $dirRelative = $relativePath ? $relativePath . '/' . $dirName : $dirName;
            
            // Apply search filter
            if ($search && !str_contains(strtolower($dirName), strtolower($search))) {
                continue;
            }

            $folderData = [
                'type' => 'directory',
                'name' => $dirName,
                'path' => $dirRelative,
                'size' => $this->getFolderSize($dir),
                'modified' => filemtime($dir),
                'children' => $this->getDirectoryStructure($dir, $dirRelative, $search, $sortBy, $filterType)
            ];
            
            $structure[] = $folderData;
        }

        // Get files
        $files = File::files($path);
        foreach ($files as $file) {
            $fileName = $file->getFilename();
            $fileRelative = $relativePath ? $relativePath . '/' . $fileName : $fileName;
            
            // Apply search filter
            if ($search && !str_contains(strtolower($fileName), strtolower($search))) {
                continue;
            }

            $extension = strtolower($file->getExtension());
            
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
                $metadata = $this->getFileMetadataFromPath($file->getPathname());
                
                // Generate correct image URL
                $imageUrl = $this->generateImageUrl($fileRelative);
                
                $fileData = [
                    'type' => 'file',
                    'name' => $fileName,
                    'path' => $fileRelative,
                    'url' => $imageUrl,
                    'size' => $metadata['size'],
                    'extension' => $extension,
                    'dimensions' => $metadata['dimensions'],
                    'modified' => $metadata['modified']
                ];
                
                // Apply filter for files
                if (!$filterType || $this->fileMatchesFilter($fileData, $filterType)) {
                    $structure[] = $fileData;
                }
            }
        }

        // Sort the structure
        $structure = $this->sortStructure($structure, $sortBy);

        return $structure;
    }

    /**
     * Generate correct image URL
     */
    private function generateImageUrl($relativePath)
    {
        // Try different possible locations for images
        $possiblePaths = [
            'images/' . $relativePath,
            'storage/images/' . $relativePath,
            'public/images/' . $relativePath,
            $relativePath
        ];

        foreach ($possiblePaths as $path) {
            $fullPath = public_path($path);
            if (File::exists($fullPath)) {
                return url($path);
            }
        }

        // If no file found, return the asset path anyway (might be a path issue)
        return url('images/' . $relativePath);
    }

    /**
     * Get file metadata from path
     */
    private function getFileMetadataFromPath($filePath)
    {
        $metadata = [
            'size' => filesize($filePath),
            'modified' => filemtime($filePath),
            'dimensions' => null
        ];

        // Get image dimensions for image files
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (function_exists('getimagesize')) {
                $imageInfo = @getimagesize($filePath);
                if ($imageInfo) {
                    $metadata['dimensions'] = $imageInfo[0] . 'x' . $imageInfo[1];
                }
            }
        }

        return $metadata;
    }

    /**
     * Get file metadata for newly uploaded files
     */
    private function getMetadataForUploadedFile($filePath)
    {
        $metadata = [
            'size' => filesize($filePath),
            'modified' => filemtime($filePath),
            'dimensions' => null
        ];

        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (function_exists('getimagesize')) {
                $imageInfo = @getimagesize($filePath);
                if ($imageInfo) {
                    $metadata['dimensions'] = $imageInfo[0] . 'x' . $imageInfo[1];
                }
            }
        }

        return $metadata;
    }

    /**
     * Generate unique filename
     */
    private function generateUniqueFileName($file)
    {
        $extension = $file->getClientOriginalExtension();
        $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $name = Str::slug($name);
        $timestamp = time();
        
        return $name . '_' . $timestamp . '.' . $extension;
    }

    /**
     * Get folder size recursively
     */
    private function getFolderSize($folderPath)
    {
        $size = 0;
        
        foreach (File::allFiles($folderPath) as $file) {
            $size += $file->getSize();
        }
        
        return $size;
    }

    /**
     * Check if folder matches filter
     */
    private function folderMatchesFilter($folderData, $filterType)
    {
        return true; // Directories always pass filter for now
    }

    /**
     * Check if file matches filter
     */
    private function fileMatchesFilter($fileData, $filterType)
    {
        return !$filterType || $fileData['type'] === $filterType;
    }

    /**
     * Sort structure by criteria
     */
    private function sortStructure($structure, $sortBy)
    {
        usort($structure, function($a, $b) use ($sortBy) {
            switch ($sortBy) {
                case 'name':
                    return strcmp($a['name'], $b['name']);
                case 'date':
                    return ($b['modified'] ?? 0) - ($a['modified'] ?? 0);
                case 'size':
                    return ($b['size'] ?? 0) - ($a['size'] ?? 0);
                case 'type':
                    return strcmp($a['type'], $b['type']);
                default:
                    return 0;
            }
        });

        return $structure;
    }

    /**
     * Search in directory
     */
    private function searchInDirectory($path, $query)
    {
        $results = [];
        
        if (!File::exists($path)) {
            return $results;
        }

        $items = collect(File::allFiles($path))->merge(File::directories($path));

        foreach ($items as $item) {
            $itemName = basename($item);
            
            if (str_contains(strtolower($itemName), strtolower($query))) {
                if (File::isDirectory($item)) {
                    $results[] = [
                        'type' => 'directory',
                        'name' => $itemName,
                        'path' => str_replace($this->basePath . '/', '', $item),
                        'size' => $this->getFolderSize($item),
                        'modified' => filemtime($item)
                    ];
                } else {
                    $extension = strtolower(pathinfo($item, PATHINFO_EXTENSION));
                    if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
                        $metadata = $this->getFileMetadataFromPath($item);
                        $results[] = [
                            'type' => 'file',
                            'name' => $itemName,
                            'path' => str_replace($this->basePath . '/', '', $item),
                            'url' => url('images/' . str_replace($this->basePath . '/', '', $item)),
                            'size' => $metadata['size'],
                            'extension' => $extension,
                            'dimensions' => $metadata['dimensions'],
                            'modified' => $metadata['modified']
                        ];
                    }
                }
            }
        }

        return $results;
    }
}
