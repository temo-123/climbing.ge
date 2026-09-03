<?php

namespace App\Services;

use App\Models\Guide\ClimbingRoutesJson;
use App\Models\Guide\MtpPitchJson;
use App\Models\Guide\SectorLocalImagesJson;
use App\Models\Guide\SectorImageExtraDrawing;
use App\Models\Guide\SectorLocalImageExtraDrawing;
use App\Models\Guide\SpotRocksImageJson;

class CanvasService
{
    /**
     * Delete all canvas JSON records (route + pitch drawings + the general
     * extra-info drawing) linked to a sector_image.
     * Call this before deleting the image file so nothing is orphaned in DB.
     */
    public static function deleteSectorImageCanvasData(int $sectorImageId): void
    {
        ClimbingRoutesJson::where('sector_image_id', $sectorImageId)->delete();
        MtpPitchJson::where('sector_image_id', $sectorImageId)->delete();
        SectorImageExtraDrawing::where('sector_image_id', $sectorImageId)->delete();
    }

    /**
     * Delete all canvas JSON records (per-sector layout drawings + the
     * general extra-info drawing) linked to a sector_local_image.
     * Call this before deleting the image file so nothing is orphaned in DB.
     */
    public static function deleteSectorLocalImageCanvasData(int $sectorLocalImageId): void
    {
        SectorLocalImagesJson::where('sector_local_image_id', $sectorLocalImageId)->delete();
        SectorLocalImageExtraDrawing::where('sector_local_image_id', $sectorLocalImageId)->delete();
    }

    /**
     * Delete all canvas JSON records (per-sector drawings) linked to a
     * spot_rocks_image. Call this before deleting the image file so nothing
     * is orphaned in DB. Redundant with the FK's onDelete('cascade') on
     * spot_rocks_image_id, but kept explicit for the same reason every other
     * method here is: consistency with how the image FILE cleanup
     * (ImageControllService::image_delete) is never itself transactional
     * with the DB delete, so an app-level call here is the reliable point.
     */
    public static function deleteSpotRocksImageCanvasData(int $spotRocksImageId): void
    {
        SpotRocksImageJson::where('spot_rocks_image_id', $spotRocksImageId)->delete();
    }
}
