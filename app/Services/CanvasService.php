<?php

namespace App\Services;

use App\Models\Guide\ClimbingRoutesJson;
use App\Models\Guide\MtpPitchJson;
use App\Models\Guide\SectorLocalImagesJson;

class CanvasService
{
    /**
     * Delete all canvas JSON records (route + pitch drawings) linked to a sector_image.
     * Call this before deleting the image file so nothing is orphaned in DB.
     */
    public static function deleteSectorImageCanvasData(int $sectorImageId): void
    {
        ClimbingRoutesJson::where('sector_image_id', $sectorImageId)->delete();
        MtpPitchJson::where('sector_image_id', $sectorImageId)->delete();
    }

    /**
     * Delete all canvas JSON records (layout drawings) linked to a sector_local_image.
     * Call this before deleting the image file so nothing is orphaned in DB.
     */
    public static function deleteSectorLocalImageCanvasData(int $sectorLocalImageId): void
    {
        SectorLocalImagesJson::where('sector_local_image_id', $sectorLocalImageId)->delete();
    }
}
