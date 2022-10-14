<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Gallery;
use App\Models\Article;
use App\Services\ImageControllService;
use File;

class GalleryController extends Controller
{
    public function index()
    {
        
    }


    public function add(Request $request)
    {
        
    }

    public function get_gallery_data(Request $request)
    {
        
    }

    public function get_articles(Request $request)
    {
    }

    public function get_image(Request $request)
    {
        return Gallery::where('id',strip_tags($request->id))->first();
    }

    public function get_editing_image(Request $request)
    {
        return Gallery::where('id',strip_tags($request->id))->first();
    }

    public function edit(Request $request)
    {
        
    }


	public function delete(gallery $gallery, Request $request)
	{
		
    }

    
}
