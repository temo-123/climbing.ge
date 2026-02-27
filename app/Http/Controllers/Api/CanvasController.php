<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CanvasController extends Controller
{
    public function saveImage(Request $request)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'image' => 'required|image|mimes:png,jpg,jpeg,gif|max:10240', // 10MB max
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid image file. Please upload a PNG image under 10MB.'
                ], 400);
            }

            // Get the uploaded file
            $image = $request->file('image');

            if (!$image) {
                return response()->json([
                    'success' => false,
                    'message' => 'No image file provided.'
                ], 400);
            }

            // Generate a unique filename
            $filename = 'canvas_' . time() . '_' . uniqid() . '.png';

            // Store the image in public/images directory
            $path = $image->storeAs('images', $filename, 'public');

            if ($path) {
                return response()->json([
                    'success' => true,
                    'message' => 'Image saved successfully!',
                    'filename' => $filename,
                    'path' => '/storage/images/' . $filename
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to save image to storage.'
                ], 500);
            }

        } catch (\Exception $e) {
            \Log::error('Canvas image save error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while saving the image.'
            ], 500);
        }
    }
}
