<?php

namespace App\Http\Controllers\Api\User\Admin\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\MountSystemService;

use App\Models\Guide\Mount;
use App\Models\Guide\Locale_mount;

use App\Models\Guide\Article_mount;
use App\Models\Guide\Article;

use Auth;
use Validator;

class MountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     return MountSystemService::get_local_mounts_use_locale(Mount::latest('id')->get(), 'us');
    // }

    // public function get_locale_mounts(Request $request)
    // {
    //     // $mounts_system = Mount::latest('id')->get();
    //     return MountSystemService::get_local_mounts_use_locale(Mount::latest('id')->get(), $request->lang);
    // }

    // public function get_locale_mount(Request $request)
    // {
    //     return MountSystemService::get_local_mount_use_locale($request->mount_id, $request->lang);
    // }

    // public function get_locale_mount_on_route_page(Request $request)
    // {
    //     $m_system = Article::where('id', '=', $request->mount_route_id)->first();
        
    //     $mount_sys = $m_system->mount_masiv;

    //     if(count($mount_sys) > 0){
    //         $mounts_system = Mount::where('id', '=', $mount_sys[0]->id)->first();

    //         $system = MountSystemService::get_local_mount_use_locale($mounts_system->id, $request->lang);

    //         return $system[0];
    //     }
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create_mount_massive(Request $request)
    {
        $validation_issets = [];

        $ka_validate = $this->local_mount_validate($request['data']['ka_data']);
        if ($ka_validate != null) {
            $validation_issets['ka_info_validation'] = $ka_validate;
        }
        else{
            $validation_issets['ka_info_validation'] = false;
        }

        $us_validate = $this->local_mount_validate($request['data']['us_data']);
        if ($us_validate != null) {
            $validation_issets['us_info_validation'] = $us_validate;
        }
        else{
            $validation_issets['us_info_validation'] = false;
        }

        $global_validate = $this->global_mount_validate($request['data']['global_data']);
        if ($global_validate != null) {
            $validation_issets['global_info_validation'] = $global_validate;
        }
        else{
            $validation_issets['global_info_validation'] = false;
        }

        if (
            !$validation_issets['global_info_validation'] && 
            // !$validation_issets['ru_info_validation'] && 
            !$validation_issets['ka_info_validation'] && 
            !$validation_issets['us_info_validation']
        ) {
            
            $saiving_issets['ka_info_status'] = $this->add_locale_mount($request['data']['ka_data'], 'ka');
            // $saiving_issets['ru_info_status'] = $this->add_locale_mount($request['data']['ru_data'], 'ru');
            $saiving_issets['us_info_status'] = $this->add_locale_mount($request['data']['us_data'], 'us');

            if (
                $saiving_issets['ka_info_status'] != 'Error' &&
                // $saiving_issets['ru_info_status'] != 'Error' &&
                $saiving_issets['us_info_status'] != 'Error'
            ) {
                $action_mount_id = $this->add_global_mount(
                    $request['data']['global_data'], 

                    $saiving_issets['ka_info_status'],
                    // $saiving_issets['ru_info_status'],
                    $saiving_issets['us_info_status']                    
                );
            }
        }
        else{            
            return response()->json([
                'validation' => $validation_issets
            ], 422);
        }
    }

    public function add_locale_mount($data, $locale)
    {
        $article = new Locale_mount;
        
        $article['title']=$data["title"];
        $article['short_description']=$data["short_description"];
        $article['text']=$data["text"];
        $article['how_get']=$data["how_get"];
        $article['best_time']=$data["best_time"];

        $article['locale']=$locale;

        $saved = $article->save();

        if(!$saved){
            App::abort(500, 'Error');
        }
        else{
            return $article->id;
        }
    }

    public function add_global_mount($global_data, $ka_info_id, $us_info_id)
    {
        $article = new Mount;

        $article['map']=$global_data["map"];
        $article['weather']=$global_data["weather"];

        $article['name'] = $global_data["name"];

        $article['us_mount_id'] = $us_info_id;
        $article['ka_mount_id'] = $ka_info_id;
        // $article['ru_mount_id'] = $ru_info_id;
        
        $article -> save();
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function show($id)
    // {
    //     $mounts_system = Mount::where('id', '=', $id)->first();
    //     $mounts_system = MountSystemService::get_locale_mount_system_in_page($mounts_system);
    //     return $mounts_system;
    // }

    public function get_editing_mount_data(Request $request)
    {
        $mounts_system = Mount::where('id', '=', $request->mount_id)->first();

        $data = [
            "global_data" => $mounts_system,
            "us_data" => $mounts_system->us_mount,
            "ka_data" => $mounts_system->ka_mount,
            // "ru_data" => $mounts_system->ru_mount,
        ];

        return $data;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit_mount_massive(Request $request)
    {
        $validation_issets = [];

        $ka_validate = $this->local_mount_validate($request['data']['ka_data']);
        if ($ka_validate != null) {
            $validation_issets['ka_info_validation'] = $ka_validate;
        }
        else{
            $validation_issets['ka_info_validation'] = false;
        }

        $us_validate = $this->local_mount_validate($request['data']['us_data']);
        if ($us_validate != null) {
            $validation_issets['us_info_validation'] = $us_validate;
        }
        else{
            $validation_issets['us_info_validation'] = false;
        }

        // $ru_validate = $this->local_mount_validate($request['data']['ru_data']);
        // if ($ru_validate != null) {
        //     $validation_issets['ru_info_validation'] = $ru_validate;
        // }
        // else{
        //     $validation_issets['ru_info_validation'] = false;
        // }

        $global_validate = $this->global_mount_validate($request['data']['global_data']);
        if ($global_validate != null) {
            $validation_issets['global_info_validation'] = $global_validate;
        }
        else{
            $validation_issets['global_info_validation'] = false;
        }

        if (
            !$validation_issets['global_info_validation'] && 
            // !$validation_issets['ru_info_validation'] && 
            !$validation_issets['ka_info_validation'] && 
            !$validation_issets['us_info_validation']
        ) {
            $local_mounts = $this->edit_global_mount(
                $request['data']['global_data'],                
            );
            
            if (
                $local_mounts['ka_info_status'] != 'Error'
            ) {
                $saiving_issets['ka_info_status'] = $this->edit_locale_mount($request['data']['ka_data'], $local_mounts['ka_mount_id']);
                // $saiving_issets['ru_info_status'] = $this->edit_locale_mount($request['data']['ru_data'], $local_mounts['ru_mount_id']);
                $saiving_issets['us_info_status'] = $this->edit_locale_mount($request['data']['us_data'], $local_mounts['us_mount_id']);
            }
        }
        else{            
            return response()->json([
                'validation' => $validation_issets
            ], 422);
        }
    }

    public function edit_locale_mount($data, $locale_mount_id)
    {
        $edit_local_data = Locale_mount::where('id', '=', $locale_mount_id)->first();
        
        $edit_local_data['title']=$data["title"];
        $edit_local_data['short_description']=$data["short_description"];
        $edit_local_data['text']=$data["text"];
        $edit_local_data['how_get']=$data["how_get"];
        $edit_local_data['best_time']=$data["best_time"];

        $saved = $edit_local_data->save();

        if(!$saved){
            App::abort(500, 'Error');
        }
    }

    public function edit_global_mount($global_data,)
    {
        // dd($global_data['id']);
        $editing_global_mount = Mount::where('id', '=', $global_data['id'])->first();

        $editing_global_mount['map']=$global_data["map"];
        $editing_global_mount['weather']=$global_data["weather"];

        $editing_global_mount['name'] = $global_data["name"];
        
        $saved = $editing_global_mount -> save();

        if(!$saved){
            App::abort(500, 'Error');
        }
        else{
            return $editing_global_mount;

            $locale_mount = [
                'us_mount_id' => $editing_article['us_mount_id'],
                'ka_mount_id' => $editing_article['ka_mount_id'],
                // 'ru_mount_id' => $editing_article['ru_mount_id'],
            ];
    
            return $locale_mount;
        }
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
        $editing_product_category = Mount::where("id", "=", $id)->first();

        $editing_product_category['map'] = $request->editing_data['map'];

        $editing_product_category -> save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted_product_category = Mount::where("id", "=", $id)->first();
        $deleted_product_category -> delete();
    }

    public function global_mount_validate($data)
    {
        $validator = Validator::make($data, [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }
    }

    public function local_mount_validate($data)
    {
        $validator = Validator::make($data, [
            'text' => 'required',
            'title' => 'required',
            'short_description' => 'required | max:190',
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }
    }
}
