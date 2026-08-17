<?php

namespace App\Http\Controllers\Api\User\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\Apps_link;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppsLinkController extends Controller
{
    public function get_all()
    {
        $auth = PermissionService::authorize('apps_link', 'show');
        if ($auth) return $auth;

        return Apps_link::orderBy('keyword')->get();
    }

    public function create(Request $request)
    {
        $auth = PermissionService::authorize('apps_link', 'add');
        if ($auth) return $auth;

        $validator = Validator::make($request->all(), [
            'keyword' => 'required|string|max:60|unique:apps_links,keyword',
            'link' => 'required|url',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $link = Apps_link::create($validator->validated());

        return response()->json($link, 201);
    }

    public function update(Request $request, $id)
    {
        $auth = PermissionService::authorize('apps_link', 'edit');
        if ($auth) return $auth;

        $link = Apps_link::find($id);
        if (!$link) {
            return response()->json(['message' => 'App link not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'keyword' => 'required|string|max:60|unique:apps_links,keyword,' . $link->id,
            'link' => 'required|url',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $link->update($validator->validated());

        return response()->json($link);
    }

    public function delete($id)
    {
        $auth = PermissionService::authorize('apps_link', 'del');
        if ($auth) return $auth;

        $link = Apps_link::find($id);
        if (!$link) {
            return response()->json(['message' => 'App link not found'], 404);
        }

        $link->delete();

        return response()->json(['message' => 'App link deleted successfully']);
    }
}
