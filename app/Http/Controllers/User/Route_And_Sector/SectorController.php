<?php

namespace App\Http\Controllers\User\Route_And_Sector;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Sector;
use App\Models\Article;

use App\Models\Sector_image;

use App\Services\imageControllService;

class SectorController extends Controller
{
    public function add(Request $request)
    {
        $request->user()->authorizeRoles(['manager', 'admin']);
        
        if ($request -> isMethod('post')) {
            $input = $request -> except('_token');

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

            // dd($sector);

            // delete product file
            // $fileName = $არტიცლე['image'];
            // $destinationPath = 'images/shop_img/';
            // File::delete($destinationPath.$fileName);

            // delete product from db
            $sector ->delete();
        }
    }

    private function sector_validate()
    {
        
            // $validator = validator::make($input, [
            //     'title' => 'required|max:190',
            //     'image' => 'required',
            //     'text' => 'max:500',
            // ]);
            // if ($validator->fails()) {
            //     return back() -> withErrors($validator) -> withInput();
            // }
    }








    public function get_sector_image(Request $request)
    {
        $sector_images = Sector_image::where('sector_id',"=", $request->sector_id)->get();
        return(
            $data = [
                "sector_images" => $sector_images,
            ]
        );
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

        $image_dir = 'sector_img';

        $article = new Sector_image();
        $article['sector_id']=$request->sector_id;

        imageControllService::image_upload($image_dir, $article, $request);

    }

    public function sector_image_update(Request $request)
    {
        $request->user()->authorizeRoles(['manager', 'admin']);

        if ($request->hasFile('profile_pic')){  
            // rename file
            $file      = $request->file('profile_pic');
            $filename  = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $pieces = explode( '.', $filename );
            $file_new_name = $pieces[0].'_('.date('Y-m-d-H-m-s').').'.$extension;
            
            // push image in folder
            $file->move(public_path('images/sector_img'), $file_new_name);

            $article = new Sector_image();
            // $article = Sector_image::where('id',strip_tags($last_global_article_id))->first();
            $article['image']=$file_new_name;
            $article -> save();

            return response()->json(["message" => "Image Update Succesfully"]);
        } 
        else{
            return response()->json(["message" => "Image Update Error."]);
        }
    }

    public function sector_image_delete(Request $request)
    {
        $request->user()->authorizeRoles(['manager', 'admin']);

        if ($request->isMethod('post')) {
            $sector_image_id=$request->id;

            $sector_image = Sector_image::where('id',strip_tags($sector_image_id))->first();
            $image_dir = "sector_img";
            
            imageControllService::image_delete($image_dir, $sector_image, $request);
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
}
