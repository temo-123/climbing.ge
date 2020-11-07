<?php

namespace App\Http\Controllers\User\Route_And_Sector;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Sector;
use App\Models\Article;

class SectorController extends Controller
{
    public function add(Request $request)
    {
        $request->user()->authorizeRoles(['manager', 'admin']);
        
        if ($request -> isMethod('post')) {
            $input = $request -> except('_token');

            $sectors = sector::where("article_id","=",$input["article_id"])->get("num");
            $sectors_count = sector::where("article_id","=",$input["article_id"])->get("num")->count();
            if($sectors_count!=0){
                $sector_num_array = array();
                foreach($sectors as $sector){
                    array_push($sector_num_array, $sector->num);
                }
                $sector_highst_num = max($sector_num_array);
                $new_sector_num = $sector_highst_num + 1;
                $input['num'] = $new_sector_num;
            }
            else{
                $input['num'] = 1;
            }
            dd($input);
            $sector = new sector();
            $sector -> fill($input);

            if ($sector->save()) {
                return redirect()->route('routes_and_sectors') -> with('status', 'sector addid'); //text
            }
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

    public function edit(sector $sector, Request $request)
    {
        $request->user()->authorizeRoles(['manager', 'admin']);
        
        if ($request->isMethod('delete')) {
            $sector ->delete();
            return redirect('admin/routes_and_sectors')->with('status', 'sector delited'); //text
        }

        if ($request->isMethod('post')) {
            $input = $request -> except('_token');
            
            $validator = Validator::make($input, [
                'name' => 'required|max:190'
            ]);
            if ($validator->fails()) {
                return redirect()->route('sectorEdit', ['sector' => $input['id']])->withErrors($validator);
            }
            
            if ($request->hasFile('image_1')) {
                $file = $request->file('image_1');
                $file -> move(public_path().'/assets/img/sector_img/',$file->getClientOriginalName());
                $input['image_1'] = $file->getClientOriginalName();
            }

            if ($sector->update()) {
                return redirect('user/routes_and_sectors')->with('status','sector updated'); //text
            }
        }

    	$old = $sector -> toArray();

    	if (view()->exists('user.components.forms.routes_and_sectors.sector.sector_edit')) {
    	    $indoor_sectors = \App\Outdoor::all();
    		$data = [
    			'name' => 'Edit sector - '.$old['name'],
    			'data' => $old,
    			
    			'indoor_sectors'=>$indoor_sectors
    		];

    		return view('user.components.forms.routes_and_sectors.sector.sector_edit', $data);
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
}
