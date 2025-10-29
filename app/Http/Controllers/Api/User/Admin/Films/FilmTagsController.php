<?php

namespace App\Http\Controllers\Api\User\Admin\Films;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Films\Film_tag;
use App\Services\PermissionService;

class FilmTagsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Film_tag::get();
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
        $auth = PermissionService::authorize('film_teg', 'add');
        // if ($auth) return $auth;
        $new_tag = new Film_tag;

        $new_tag['us_name'] = $request->data['us_name'];
        $new_tag['ka_name'] = $request->data['ka_name'];
        $new_tag['ru_name'] = $request->data['ru_name'];

        $new_tag -> save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Film_tag::where("id", "=", $id)->first();
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
        $auth = PermissionService::authorize('film_teg', 'edit');
        // if ($auth) return $auth;
        $editing_tag = Film_tag::where("id", "=", $id)->first();
        
        $editing_tag['us_name'] = $request->editing_data['us_name'];
        $editing_tag['ka_name'] = $request->editing_data['ka_name'];
        $editing_tag['ru_name'] = $request->editing_data['ru_name'];

        $editing_tag -> save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $auth = PermissionService::authorize('film_teg', 'del');
        // if ($auth) return $auth;
        $deleted_tag = Film_tag::where("id", "=", $id)->first();
        $deleted_tag -> delete();
    }
}
