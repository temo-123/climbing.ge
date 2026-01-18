<?php

namespace App\Http\Controllers\Api\User\Admin\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Abstract\ImageControllService;
use App\Services\PermissionService;

use App\Models\Guide\Sector_local_image;
use App\Models\Guide\Sector_local_image_sector;
use App\Models\Guide\SectorLocalImagesJson;
use App\Models\Guide\SectorLocalImagesJsonSector;
use App\Models\Guide\Sector;

class SectorLocalImagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     return Sector_local_image::latest('id')->get();
    // }

    public function get_editing_sectors(Request $request)
    {
        $auth = PermissionService::authorize('sector_local_image', 'view');
        if ($auth) return $auth;
        
        $sector_local_image = Sector_local_image::where("id", "=", $request->image_id)->first();
        return $sector_local_image->sectors;
    }

    public function get_editing_locale_image($id)
    {
        $auth = PermissionService::authorize('sector_local_image', 'view');
        if ($auth) return $auth;
        
        $sector_local_image = Sector_local_image::where("id", "=", $id)->first();
        $sector_local_image_sectors = $sector_local_image->sectors;
        
        $data = [
            'sector_local_image'=>$sector_local_image,
            'sector_local_image_sectors'=>$sector_local_image_sectors,
        ];
        return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function get_sector_local_img_for_modal($id)
    // {
    //     $sector_local_image = Sector_local_image::where("id", "=", $id)->first();

    //     $data = [
    //         "image" => $sector_local_image,
    //         "sectors" => $sector_local_image->sectors,
    //         "related_jsons" => $sector_local_image->jsons
    //     ];

    //     return $data;
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add_sector_local_image(Request $request)
    {
        $auth = PermissionService::authorize('sector_local_image', 'add');
        if ($auth) return $auth;
        
        $new_sector_local_image = new Sector_local_image;

        $new_sector_local_image['title'] = $request['title'];

        if($request->hasFile('image')){
            $file_new_name = ImageControllService::image_upload('images/sector_local_img/', $request, 'image');
            $new_sector_local_image['image'] = $file_new_name;
        }

        $new_sector_local_image -> save();

        $array = json_decode($request->image_sectors, true );
        foreach ($array as $image_sector) {
            $new_sector_local_image_sector = new Sector_local_image_sector;
            $new_sector_local_image_sector['image_id'] = $new_sector_local_image['id'];
            $new_sector_local_image_sector['sector_id'] = $image_sector['id'];
            $new_sector_local_image_sector -> save();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function show($id)
    // {
    //     $sector_local_image = Sector_local_image::where("id", "=", $id)->first();
    //     $sector_local_image_sectors = $sector_local_image->sectors;
        
    //     $data = [
    //         'sector_local_image'=>$sector_local_image,
    //         'sector_local_image_sectors'=>$sector_local_image_sectors,
    //     ];
    //     return $data;
    // }

    // public function sector()
    // {
    //     return $this->belongsTo(Sector::class, 'sector_id');
    // }
    
    public function save_canvas_data(Request $request, $sector_id)
    {
        $auth = PermissionService::authorize('sector_local_image', 'edit');
        if ($auth) return $auth;
        
        $json = json_encode($request->canvasData);

        // Find the sector_local_image
        $sectorLocalImage = Sector_local_image::find($request->sector_local_image_id ?? $sector_id);

        $jsonRecord = null;
        if ($request->layoutId) {
            // Update existing layout
            $jsonRecord = SectorLocalImagesJson::find($request->layoutId);
            if ($jsonRecord) {
                $jsonRecord->json = $json;
                $jsonRecord->save();
            }
        } else {
            // Create new layout - check if there's an existing layout for the first selected sector
            $existingLayout = null;
            if ($request->selectedSectors && count($request->selectedSectors) > 0) {
                $firstSectorId = $request->selectedSectors[0];
                $existingLayout = SectorLocalImagesJson::whereHas('sectors', function($query) use ($firstSectorId) {
                    $query->where('sector_id', $firstSectorId);
                })->where('sector_local_image_id', $sectorLocalImage->id)->first();
            }

            if ($existingLayout) {
                // Update existing layout with the same sector name
                $existingLayout->json = $json;
                $existingLayout->save();
                $jsonRecord = $existingLayout;
            } else {
                // Create new layout
                $jsonRecord = SectorLocalImagesJson::create([
                    'sector_local_image_id' => $sectorLocalImage->id,
                    'json' => $json
                ]);
            }
        }

        // Update sector relations for this layout
        if ($jsonRecord && $request->selectedSectors) {
            // Remove existing relations
            SectorLocalImagesJsonSector::where('sect_loc_img_json_id', $jsonRecord->id)->delete();
            // Add new relations
            foreach ($request->selectedSectors as $sectorId) {
                SectorLocalImagesJsonSector::create([
                    'sect_loc_img_json_id' => $jsonRecord->id,
                    'sector_id' => $sectorId
                ]);
            }
        }

        return response()->json(['message' => 'Canvas data saved successfully']);
    }

    // public function get_layout($layout_id)
    // {
    //     $layout = SectorLocalImagesJson::with('sectors')->find($layout_id);

    //     return response()->json(['layout' => $layout]);
    // }

    // public function get_layouts($sector_local_image_id)
    // {
    //     $layouts = SectorLocalImagesJson::where('sector_local_image_id', $sector_local_image_id)->with('sectors')->get();

    //     return response()->json(['layouts' => $layouts]);
    // }

    // public function get_layout_old($sector_id)
    // {
    //     $sector = Sector::find($sector_id);
    //     $jsons = $sector->sector_local_images_jsons;

    //     return response()->json(['jsons' => $jsons]);
    // }
    
    public function update_image(Request $request, $id)
    {
        $auth = PermissionService::authorize('sector_local_image', 'edit');
        if ($auth) return $auth;
        
        $editing_sector_local_image = Sector_local_image::where("id", "=", $id)->first();

        $editing_sector_local_image['title'] = $request['title'];

        if($request->hasFile('image')){
            $file_new_name = ImageControllService::image_update('images/sector_local_img/', $editing_sector_local_image, $request, 'image', 'image');
            $editing_sector_local_image['image'] = $file_new_name;
        }

        $editing_sector_local_image -> save();

        $array = json_decode($request->image_sectors, true );

        foreach ($array as $image_sector) {
            // foreach ($variable as $key => $value) {
            //     # code...
            // }
            $item_count = Sector_local_image_sector::where("image_id", "=", $editing_sector_local_image['id'])->where("sector_id", "=", $image_sector['id'])->count();
            if($item_count == 0){
                $new_sector_local_image_sector = new Sector_local_image_sector;
                $new_sector_local_image_sector['image_id'] = $editing_sector_local_image['id'];
                $new_sector_local_image_sector['sector_id'] = $image_sector['id'];
                $new_sector_local_image_sector -> save();
            }
            else{

            }
        }

        // dd('This method have sector deliting action problem');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function del_locale_image($id)
    {
        $auth = PermissionService::authorize('sector_local_image', 'del');
        if ($auth) return $auth;
        
        $deleting_sector_local_images = Sector_local_image::where("id", "=", $id)->first();

        $image_sector_relations = Sector_local_image_sector::where("image_id", "=", $deleting_sector_local_images->id)->get();

        if (count($image_sector_relations) > 0) {
            foreach ($image_sector_relations as $image_sector_relation) {
                $image_sector_relation ->delete();
            }
        }
        
        ImageControllService::image_delete('images/sector_local_img/', $deleting_sector_local_images, 'image');

        $deleting_sector_local_images -> delete();
    }

    public function del_image_sector_from_db(Request $request)
    {
        $deleted_sector = Sector_local_image_sector::where("image_id", "=", $request->image_id)->where("sector_id", "=", $request->sector_id)->first();
        $deleted_sector -> delete();

        return 'Deliting socsesful';
    }
}
