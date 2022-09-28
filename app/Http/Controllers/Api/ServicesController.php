<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Service;
use App\Services\GetServicesService;

class ServicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $global_services = Service::get();
        // return $services = GetServicesService::get_locale_services_use_locale($global_services, $request->lang);
    }

    public function get_local_services(Request $request)
    {
        $global_services = Service::where('published', '=', 1)->get();
        return $services = GetServicesService::get_locale_services_use_locale($global_services, $request->lang);
    }

    public function get_similar_service(Request $request)
    {
        $global_services = Service::where('published', '=', 1)->where('id', '!=', $request->id)->get();
        return $services = GetServicesService::get_locale_services_use_locale($global_services, $request->lang);
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
    public function show($url_title)
    {
        // dd($url_title);
        // $global_service = Service::where('published', '=', 1)->where('url_title',strip_tags($url_title))->first();
        // return $service = GetServicesService::get_locale_service_in_page($global_service);
    }

    public function get_local_service_in_page(Request $request)
    {  
        $global_service = Service::where('published', '=', 1)->where('url_title',strip_tags($request->url_title))->first();
        return $service = GetServicesService::get_locale_service_in_page_use_locale($global_service, $request->lang);
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
        $page_service = Service::latest('id')
                            ->where('published', '=', 1)
                            ->where('id', '=', $id)
                            ->first();

        $global_services = Service::latest('id')
                            ->where('published', '=', 1)
                            ->where('id', '!=', $page_service->id)
                            // ->where('url_title', '!=', $request->url_title)
                            // ->where('category_id', '=', $page_service->category_id)
                            ->get();
                            
        return $services = GetServicesService::get_locale_services($global_services);
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