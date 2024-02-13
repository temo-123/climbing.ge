<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator;

use App\Models\General_info;

class GeneralInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return General_info::get();
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
        $validate = $this->validation($request);

        if ($validate != null) {
            return($validate);
        }
        else{
            $new_general_info = new General_info;

            $new_general_info['title'] = $request->data['title'];

            $new_general_info['text_us'] = $request->data['us_text'];
            $new_general_info['text_ka'] = $request->data['ka_text'];
            $new_general_info['text_ru'] = $request->data['ru_text'];

            $new_general_info -> save();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return General_info::where("id", "=", $id)->first();
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
        $validate = $this->validation($request);

        if ($validate != null) {
            return($validate);
        }
        else{
            $editing_general_info = General_info::where("id", "=", $id)->first();
    
            $editing_general_info['title'] = $request->data['title'];
    
            $editing_general_info['text_us'] = $request->data['us_text'];
            $editing_general_info['text_ka'] = $request->data['ka_text'];
            $editing_general_info['text_ru'] = $request->data['ru_text'];
    
            $editing_general_info -> save();
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
        $deleted_general_info = General_info::where("id", "=", $id)->first();
        $deleted_general_info -> delete();
    }

    public function validation($request)
    {
        $validator = validator($data = $request->data, [
            'title' => 'required',
            'us_text' => 'required',
            'ru_text' => 'required',
            'ka_text' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->messages(),
            ], 422);
        }
    }
}
