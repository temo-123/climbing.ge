<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Region;
use Validator;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Region::get();
    }

    public function locale_regions(Request $request)
    {
        $data = [];
        if($request -> lang == 'ru'){
            $regions = Region::select('id', 'ru_name', 'ru_text')->get();

            foreach ($regions as $region) {
                array_push($data, ['id'=>$region->id,'name'=>$region->ru_name, 'text'=>$region->ru_text]);
            }
        }
        else if($request -> lang == 'ka'){
            $regions = Region::select('id', 'ka_name', 'ka_text')->get();

            foreach ($regions as $region) {
                array_push($data, ['id'=>$region->id,'name'=>$region->ka_name, 'text'=>$region->ka_text]);
            }
        }
        else{
            $regions = Region::select('id', 'us_name', 'us_text')->get();

            foreach ($regions as $region) {
                array_push($data, ['id'=>$region->id,'name'=>$region->us_name, 'text'=>$region->us_text]);
            }
        }

        return $data;
    }


    public function locale_region(Request $request)
    {
        // dd($request->lang, $request->region_id);

        $data = [];
        if($request -> lang == 'ru'){
            $regions = Region::select('id', 'ru_name', 'ru_text', 'map')->where('id',strip_tags($request->region_id))->get();

            foreach ($regions as $region) {
                array_push($data, ['id'=>$region->id,'name'=>$region->ru_name, 'text'=>$region->ru_text, "map"=>$region->map]);
            }
        }
        else if($request -> lang == 'ka'){
            $regions = Region::select('id', 'ka_name', 'ka_text', 'map')->where('id',strip_tags($request->region_id))->get();

            foreach ($regions as $region) {
                array_push($data, ['id'=>$region->id,'name'=>$region->ka_name, 'text'=>$region->ka_text, "map"=>$region->map]);
            }
        }
        else{
            $regions = Region::select('id', 'us_name', 'us_text', 'map')->where('id',strip_tags($request->region_id))->get();

            foreach ($regions as $region) {
                array_push($data, ['id'=>$region->id,'name'=>$region->us_name, 'text'=>$region->us_text, "map"=>$region->map]);
            }
        }

        return $data;
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
    public function show(Request $request, $id)
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
        $validate = $this->region_validate($request->data);

        if ($validate != null) {
            return($validate);
        }
        else{
            $new_region = new Region;

            $new_region['us_name']=$request->data["us_name"];
            $new_region['ru_name']=$request->data["ru_name"];
            $new_region['ka_name']=$request->data["ka_name"];

            $new_region['us_text']=$request->data["us_text"];
            $new_region['ru_text']=$request->data["ru_text"];
            $new_region['ka_text']=$request->data["ka_text"];

            $new_region['map']=$request->data["map"];

            $new_region -> save();
        }
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


    public function region_validate($data)
    {
        $validator = Validator::make($data, [
            'us_name' => 'required',
            'ru_name' => 'required',
            'ka_name' => 'required',
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }
        // dd($validator->messages());
    }
}
