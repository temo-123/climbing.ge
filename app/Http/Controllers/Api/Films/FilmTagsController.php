<?php

namespace App\Http\Controllers\Api\Films;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Films\Film_tag;

class FilmTagsController extends Controller
{
    public function index()
    {
        return Film_tag::get();
    }

    public function show($id)
    {
        return Film_tag::where("id", "=", $id)->first();
    }
}
