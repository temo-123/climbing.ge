<?php

namespace App\Http\Controllers\User\Route_And_Sector;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Sector;
use App\Models\Article;
use App\Models\Sector_image;
use App\Services\ImageControllService;

use Validator;

class SectorController extends Controller
{
    public function add(Request $request)
    {
        $request->user()->authorizeRoles(['manager', 'admin']);
        
        if ($request -> isMethod('post')) {
            $input = $request -> except('_token');

            $this->sector_validate($request);
            // dd($request);

            // $sectors = sector::where("article_id","=",$input["article_id"])->get("num");
            // $sectors_count = sector::where("article_id","=",$input["article_id"])->get("num")->count();
            // if($sectors_count!=0){
            //     $sector_num_array = array();
            //     foreach($sectors as $sector){
            //         array_push($sector_num_array, $sector->num);
            //     }
            //     $sector_highst_num = max($sector_num_array);
            //     $new_sector_num = $sector_highst_num + 1;
            //     $input['num'] = $new_sector_num;
            // }
            // else{
            //     $input['num'] = 1;
            // }
            // $sector = new sector();
            // $sector -> fill($input);
            $sectors = sector::where("name","=","temporary_sector")->get();
            foreach ($sectors as $sectors) {
                $last_sector_id = $sectors->id;
            }

            $sector = sector::find($last_sector_id);
            // $sector = new sector();

            $sector['article_id'] = $request->article_id;
            $sector['name'] = $request->name;
            $sector['text'] = $request->text;
            $sector['all_day_in_shade'] = $request->all_day_in_shade;
            $sector['all_day_in_sun'] = $request->all_day_in_sun;
            $sector['in_the_shade_afternoon'] = $request->in_the_shade_afternoon;
            $sector['in_the_shade_befornoon'] = $request->in_the_shade_befornoon;
            $sector['in_shade_after_10'] = $request->in_shade_after_10;
            $sector['in_shade_after_15'] = $request->in_shade_after_15;
            $sector['slabby'] = $request->slabby;
            $sector['vertical'] = $request->vertical;
            $sector['overhang'] = $request->overhang;

            $sector -> save();
        }

        if (view() -> exists('user.components.forms.routes_and_sectors.sector.sector_add')) {
            $regions = Article::where("category","=","outdoor")->get();
            $data=[
                'tegions_name' => $regions,
                'title' => 'New sector',
                'back_btn' => 'home',
                'add_title' => 'Add sector',
                'add_active' => 'Add sector',
                
                'add_form' => 'sectorAdd',
                
                'url_title' => 1,
                'text' => 1, 
                'published'=>'1',
                'link'=>'1',
                'sector_filtr'=>'1',
                
                'image' => 'sector_img',
            ];
            return view('user.components.forms.routes_and_sectors.sector.sector_add', $data);
        }
        abort(404);
    }

    public function edit_form(Request $request)
    {
        if (view()->exists('user.components.forms.routes_and_sectors.sector.sector_edit')) {
            $sector = sector::where('id',strip_tags($request->id))->first();;
            // dd($sector->name);
    		$data = [
    			'name' => 'Edit sector - '.$sector['name'],
    			
    			'editing_sector_id'=>$sector->id
    		];

    		return view('user.components.forms.routes_and_sectors.sector.sector_edit', $data);
        }
    }

    public function edit(sector $sector, Request $request)
    {
        $request->user()->authorizeRoles(['manager', 'admin']);

        if ($request->isMethod('post')) {
            $input = $request -> except('_token');
            
            $this->sector_validate($request);
            
            $sector = sector::find($request->id);
            // $sector = new sector();

            // dd($sector);

            $sector['article_id'] = $request->article_id;
            $sector['name'] = $request->name;
            $sector['text'] = $request->text;
            $sector['all_day_in_shade'] = $request->all_day_in_shade;
            $sector['all_day_in_sun'] = $request->all_day_in_sun;
            $sector['in_the_shade_afternoon'] = $request->in_the_shade_afternoon;
            $sector['in_the_shade_befornoon'] = $request->in_the_shade_befornoon;
            $sector['in_shade_after_10'] = $request->in_shade_after_10;
            $sector['in_shade_after_15'] = $request->in_shade_after_15;
            $sector['slabby'] = $request->slabby;
            $sector['vertical'] = $request->vertical;
            $sector['overhang'] = $request->overhang;

            $sector -> update();

            // if ($sector->update()) {
            //     return redirect('user/routes_and_sectors')->with('status','sector updated'); //text
            // }
        }
    }

    public function delete(Request $request)
    {
        $request->user()->authorizeRoles(['manager', 'admin']);

        if ($request->isMethod('post')) {
            $sector_id=$request->id;

            $sector = Sector::where('id',strip_tags($sector_id))->first();

            // delete product file
            $sector_images = Sector_image::where('sector_id',strip_tags($sector_id))->get();
            $sector_images_count = Sector_image::where('sector_id',strip_tags($sector_id))->count();
            // dd($sector_images_count);
            if ($sector_images_count > 0) {
                foreach ($sector_images as $sector_image) {
                    imageControllService::image_delete('images/sector_img/', $sector_image, $request);
                    $sector_image ->delete();
                }
            }

            // delete product from db
            $sector ->delete();
        }
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

    public function create_temporary_sector()
    {
        $sector = new Sector();
        $sector['name'] = 'temporary_sector';
        $sector -> save();
    }

    public function delete_temporary_sector(Request $request)
    {
        if ($request->isMethod('post')) {
            $sector_id=$request->sector_id;

            $deleting_sector_images = Sector_image::where('sector_id','=',$sector_id)->get();
            $count_deleting_sector_images = Sector_image::where('sector_id','=',$sector_id)->count();
            
            if ($count_deleting_sector_images > 0) {
                foreach ($deleting_sector_images as $sector_image) {
                    $deliting_sector_image = Sector_image::where('id',strip_tags($sector_image->id))->first();
                    $deliting_sector_image ->delete();
                }
            }

            $sector = Sector::where('id',strip_tags($sector_id))->first();
            $sector ->delete();
        }
    }

    public function get_temporary_sector_editing_data()
    {
        $temporary_sector = sector::where("name","=","temporary_sector")->get();
        foreach ($temporary_sector as $sector) {
            $last_temporary_sectore_id = $sector->id;
        }

        return(
            $data = [
                "last_temporary_sectore_id" => $last_temporary_sectore_id,
            ]
        );
    }



    public function sector_image_validate($request)
    {
        $request->validate([
            // 'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    }

    private function sector_validate($request)
    {
        $request->validate([
            'name' => 'required|max:190',
            'article_id' => 'required'
        ]);
    }
}
