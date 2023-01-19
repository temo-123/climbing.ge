<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ImageControllService;

use App\Models\Sector_local_image;
use App\Models\Sector_local_image_sector;

class SectorLocalImagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Sector_local_image::latest('id')->get();
    }

    public function get_editing_sectors(Request $request)
    {
        $sector_local_image = Sector_local_image::where("id", "=", $request->image_id)->first();
        return $sector_local_image->sectors;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
    public function show($id)
    {
        $sector_local_image = Sector_local_image::where("id", "=", $id)->first();
        $sector_local_image_sectors = $sector_local_image->sectors;
        
        $data = [
            'sector_local_image'=>$sector_local_image,
            'sector_local_image_sectors'=>$sector_local_image_sectors,
        ];
        return $data;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_image(Request $request, $id)
    {
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
    public function destroy($id)
    {
        $deleted_product_category = Sector_local_image::where("id", "=", $id)->first();
        ImageControllService::image_delete('images/sector_local_img/', $deleted_product_category, 'image');
        $deleted_product_category -> delete();
    }

    public function del_image_sector_from_db(Request $request)
    {
        $deleted_sector = Sector_local_image_sector::where("image_id", "=", $request->image_id)->where("sector_id", "=", $request->sector_id)->first();
        $deleted_sector -> delete();

        return 'Deliting socsesful';
    }
}
