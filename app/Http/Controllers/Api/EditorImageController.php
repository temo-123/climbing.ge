<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class EditorImageController extends Controller
{
    /**
     * Image pasted / inserted in a Quill editor (big / small / mini editor).
     *
     * Saved next to every other site image, in public/images/editor_img/
     * (served directly; the web server can write there). It used to go to
     * storage/app/public/uploads/editor, which the web server user could not
     * create and which needs the `storage:link` symlink to be reachable.
     * The URL is root-relative, so the HTML stored in the text keeps working
     * on any domain (local / live).
     */
    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|file|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ]);

        $file = $request->file('image');
        $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $filename = time() . '_' . Str::random(6) . '_'
            . (preg_replace('/[^a-zA-Z0-9_-]/', '_', Str::limit($name, 60, '')) ?: 'image')
            . '.' . strtolower($file->getClientOriginalExtension() ?: $file->guessExtension());

        $dir = public_path('images/editor_img');
        File::ensureDirectoryExists($dir, 0775);
        $file->move($dir, $filename);

        return response()->json([
            'url' => '/images/editor_img/' . $filename,
        ]);
    }
}
