<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SectorImagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }






    


    public function get_sector_image(Request $request)
    {
        $sector_images = Sector_image::where('sector_id',"=", $request->sector_id)->orderBy('num')->get();
        $sector_images_count = Sector_image::where('sector_id',"=", $request->sector_id)->orderBy('num')->count();

        $sector_images_size = 100;
        if($sector_images_count > 1){
            $sector_images_size = 100 / $sector_images_count;
            $sector_images_size = $sector_images_size - 1;
        }

        return(
            $data = [
                "sector_images" => $sector_images,
                "sector_images_size" => $sector_images_size,
            ]
        );
    }

    public function save_sector_images_sequence(Request $request)
    {
        $image_num = 0;
        $images_sequence = $request->sector_images_sequence;
        foreach ($images_sequence as $image_sequence) {
            $image_sequence_id = $image_sequence['id'];
            $image = Sector_image::where('id',strip_tags($image_sequence_id))->first();
            // var_dump($image);
            // dd($image_sequence_id);
            $image_num++;
            // http_response_code(500);
            // dd('$image_num');
            $image['num'] = $image_num;
            $image->update();
        }
    }

    public function get_sector_editing_data(Request $request)
    {
        $sector = Sector::where('id',strip_tags($request->id))->first();
        return(
            $data = [
                "sector" => $sector,
            ]
        );
    }

    public function sector_image_upload(Request $request)
    {
        $request->user()->authorizeRoles(['manager', 'admin']);

        $this->sector_image_validate($request);

        $sector = new Sector_image();
        $sector['sector_id']=$request->sector_id; 

        $file_new_name = ImageControllService::image_upload('images/sector_img/', $request, 'profile_pic', 0);

        $sector['image'] = $file_new_name;
        $sector -> save();
    }

    public function sector_image_delete(Request $request)
    {
        $request->user()->authorizeRoles(['manager', 'admin']);

        if ($request->isMethod('post')) {
            $sector_image = Sector_image::where('id',strip_tags($request->id))->first();

            ImageControllService::image_delete('images/sector_img/', $sector_image, 'image');

            $sector_image -> delete();
        }
    }


    public static function get_region_image(Request $request){
        $region_image = Article::where('id',strip_tags($request->region_id))->get('climbing_area_image');
        return $region_image;
    }
}
