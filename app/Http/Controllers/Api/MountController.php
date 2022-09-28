<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Mount;
use App\Services\GetMountSystemService;

class MountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return GetMountSystemService::get_local_mounts_use_locale(Mount::latest('id')->get(), 'us');
    }

    public function get_locale_mounts(Request $request)
    {
        // $mounts_system = Mount::latest('id')->get();
        return GetMountSystemService::get_local_mounts_use_locale(Mount::latest('id')->get(), $request->lang);
    }

    public function get_locale_mount(Request $request)
    {
        return GetMountSystemService::get_local_mount_use_locale($request->mount_id, $request->lang);
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
        $mounts_system = Mount::where('id', '=', $id)->first();
        $mounts_system = GetMountSystemService::get_locale_mount_system_in_page($mounts_system);
        return $mounts_system;
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
}
