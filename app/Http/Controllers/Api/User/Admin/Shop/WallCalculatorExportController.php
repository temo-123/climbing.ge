<?php

namespace App\Http\Controllers\Api\User\Admin\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PermissionService;
use App\Services\WallDrawingService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class WallCalculatorExportController extends Controller
{
    protected WallDrawingService $drawing;

    public function __construct(WallDrawingService $drawing)
    {
        $this->drawing = $drawing;
    }

    /**
     * Export the currently configured wall (from the shop's wall price
     * calculator) as a detailed construction PDF: 3D renders captured
     * client-side, real orthographic 2D drawings computed here from the
     * same dimensions, full pricing breakdown, and — for stand-free
     * structures — a materials/bill-of-quantities section.
     *
     * Every wall-shape value used below is taken directly from what the
     * user configured in the calculator (sent as plain numbers/strings in
     * the request), not re-derived from any stored record — this export
     * has no database model of its own, it's a snapshot of the live 3D
     * preview's own inputs.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function exportWallPdf(Request $request)
    {
        $auth = PermissionService::authorize('wall_calculator', 'export');
        if ($auth) return $auth;

        try {
            $data = $request->validate([
                'width' => 'required|numeric|min:0',
                'height' => 'required|numeric|min:0',
                'depth' => 'nullable|numeric|min:0',
                'discipline' => 'nullable|string',
                'structure' => 'nullable|string',
                'sides_count' => 'nullable|integer|min:1|max:4',
                'extra_sides' => 'nullable|array',
                'extra_sides.*.width' => 'nullable|numeric',
                'extra_sides.*.depth' => 'nullable|numeric',
                'mat' => 'nullable|array',
                'mat.width' => 'nullable|numeric',
                'mat.height' => 'nullable|numeric',
                'mat.depth' => 'nullable|numeric',
                'holds_enabled' => 'nullable|boolean',
                'holds_count' => 'nullable|integer|min:0',
                'rope_enabled' => 'nullable|boolean',
                'price' => 'nullable|array',
                'bill_of_materials' => 'nullable|array',
                'bill_of_materials.*.item' => 'nullable|string',
                'bill_of_materials.*.quantity' => 'nullable',
                'bill_of_materials.*.unit' => 'nullable|string',
                'snapshots' => 'nullable|array',
                'snapshots.*.label' => 'nullable|string',
                'snapshots.*.data_url' => 'nullable|string',
            ]);

            $depth = $data['depth'] ?? 0;
            $sidesCount = $data['sides_count'] ?? 1;
            $isStandFree = str_starts_with($data['structure'] ?? '', 'standfree');
            $isBouldering = ($data['discipline'] ?? '') === 'bouldering';

            // Side 1 (width/depth/mat height as its own straight-base hint)
            // plus any extra sides — the exact same all_sides shape the 3D
            // viewer builds from, so the top/plan view always matches it.
            $sides = [[
                'width' => $data['width'],
                'depth' => $depth,
            ]];
            foreach (($data['extra_sides'] ?? []) as $extra) {
                $w = $extra['width'] ?? 0;
                if ($w > 0) {
                    $sides[] = ['width' => $w, 'depth' => $extra['depth'] ?? 0];
                }
            }

            $matHeightHint = ($isBouldering && !empty($data['mat']['height']))
                ? $data['mat']['height']
                : null;
            // Same vertHeight derivation as buildBoulderingModel (JS) — a
            // short straight base section, not an arbitrary fraction.
            $vertHeight = null;
            if ($isBouldering) {
                $vertHeight = $matHeightHint
                    ? min(max($matHeightHint, 0.15), $data['height'] - 0.3)
                    : min($data['height'] * 0.3, 1.0, $data['height'] - 0.3);
            }

            $viewData = [
                'input' => $data,
                'sides' => $sides,
                'sidesCount' => $sidesCount,
                'isStandFree' => $isStandFree,
                'isBouldering' => $isBouldering,
                'isSportClimbing' => ($data['discipline'] ?? '') === 'sport_climbing',
                'ropeEnabled' => $data['rope_enabled'] ?? false,
                'price' => $data['price'] ?? [],
                'billOfMaterials' => $data['bill_of_materials'] ?? [],
                'snapshots' => $data['snapshots'] ?? [],
                'frontViewSvg' => $this->drawing->buildFrontView($data['width'], $data['height']),
                'sideViewSvg' => $this->drawing->buildSideView($data['height'], $depth, $vertHeight),
                'topViewSvg' => $this->drawing->buildTopView($sides),
                'generatedAt' => now()->format('Y-m-d H:i'),
            ];

            $pdf = Pdf::loadView('pdf.wall_calculator', $viewData)->setPaper('a4', 'portrait');

            $filename = 'wall-configuration-' . date('Y-m-d-His') . '.pdf';
            return $pdf->download($filename);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Invalid wall configuration', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Wall calculator PDF export error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['message' => 'Error generating PDF: ' . $e->getMessage()], 500);
        }
    }
}
