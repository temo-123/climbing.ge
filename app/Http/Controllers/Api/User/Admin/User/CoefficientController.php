<?php

namespace App\Http\Controllers\Api\User\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\Coefficient;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CoefficientController extends Controller
{
    public function get_all()
    {
        if ($auth = PermissionService::authorize('coefficient', 'show')) return $auth;

        return Coefficient::orderBy('slug')->get();
    }

    public function get_coefficient($id)
    {
        if ($auth = PermissionService::authorize('coefficient', 'show')) return $auth;

        $coefficient = Coefficient::find($id);
        if (!$coefficient) {
            return response()->json(['message' => 'Coefficient not found'], 404);
        }

        return $coefficient;
    }

    public function create(Request $request)
    {
        if ($auth = PermissionService::authorize('coefficient', 'add')) return $auth;

        $validator = Validator::make($request->all(), [
            'slug' => 'required|string|max:100|regex:/^[a-z0-9_]+$/|unique:coefficients,slug',
            'value' => 'required|numeric|between:-9999999999,9999999999',
            'description' => 'nullable|string|max:1000',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $coefficient = Coefficient::create($validator->validated());

        return response()->json($coefficient, 201);
    }

    public function update(Request $request, $id)
    {
        if ($auth = PermissionService::authorize('coefficient', 'edit')) return $auth;

        $coefficient = Coefficient::find($id);
        if (!$coefficient) {
            return response()->json(['message' => 'Coefficient not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'slug' => 'required|string|max:100|regex:/^[a-z0-9_]+$/|unique:coefficients,slug,' . $coefficient->id,
            'value' => 'required|numeric|between:-9999999999,9999999999',
            'description' => 'nullable|string|max:1000',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $coefficient->update($validator->validated());

        return response()->json($coefficient);
    }

    public function delete($id)
    {
        if ($auth = PermissionService::authorize('coefficient', 'del')) return $auth;

        $coefficient = Coefficient::find($id);
        if (!$coefficient) {
            return response()->json(['message' => 'Coefficient not found'], 404);
        }

        $coefficient->delete();

        return response()->json(['message' => 'Coefficient deleted successfully']);
    }
}
