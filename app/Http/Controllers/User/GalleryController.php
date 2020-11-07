<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Gallery;

class GalleryController extends Controller
{
    public function index()
    {
        $articles = Gallery::get();
        $title = 'Events';
        $page_name = $title;
        $table_1_name = $title;
        $articles_add_url = 'article_add';
        $article_add_category = 'event';
        $articles_edit_url = 'article_edit';
        $article_page_utl = 'index';   
        $data = [
            'title'=>$title,
            'table_1'=>$articles,

            'table_1_add_url'=>$articles_add_url,
            'table_1_add_category'=>$article_add_category,
            'table_1_edit_url'=>$articles_edit_url,
            'table_1_article_url'=>$article_page_utl,
            'table_1_title'=>'gallery',
            'table_1_pablic' => '',
            'table_1_name'=> $table_1_name,
            
            'page_name' => $page_name,
            'active' => 'galley',
            'page_route' => 'outdoor_page',
        ];
        return view('user.gallery_list', $data);
    }
    public function add()
    {
        # code...
    }
    public function edit()
    {
        # code...
    }
}
