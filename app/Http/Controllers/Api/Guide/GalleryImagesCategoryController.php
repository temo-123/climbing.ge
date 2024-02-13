<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Gallery_category;

class GalleryImagesCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Gallery_category::get();
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
        $new_gallery_category = new Gallery_category;

        $new_gallery_category['us_name'] = $request->data['us_name'];
        $new_gallery_category['ka_name'] = $request->data['ka_name'];
        $new_gallery_category['ru_name'] = $request->data['ru_name'];

        $new_gallery_category -> save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Gallery_category::where("id", "=", $id)->first();
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
        $editing_gallery_category = Gallery_category::where("id", "=", $id)->first();

        $editing_gallery_category['us_name'] = $request->editing_data['us_name'];
        $editing_gallery_category['ka_name'] = $request->editing_data['ka_name'];
        $editing_gallery_category['ru_name'] = $request->editing_data['ru_name'];

        $editing_gallery_category -> save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted_gallery_category = Gallery_category::where("id", "=", $id)->first();
        $deleted_gallery_category -> delete();
    }
}
