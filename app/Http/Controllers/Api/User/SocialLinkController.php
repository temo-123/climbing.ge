<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Site_social_link;
use Validator;

class SocialLinkController extends Controller
{
    public function get_site_social_links()
    {
        return Site_social_link::get();
    }
    
    public function add_site_social_links(Request $request)
    {
        $validate = $this->site_social_links_validate($request->data);

        if ($validate != null) {
            return($validate);
        }
        else{
            $new_social_site = new Site_social_link;

            $new_social_site['url']=$request->data["url"];

            if($request->data["title"]){
                $new_social_site['title']=$request->data["title"];
            }

            $new_social_site -> save();
        }
    }

    public function del_site_social_links(Request $request)
    {
        $delited_item = Site_social_link::where('id', '=', $request->link_id)->first();
        $delited_item ->delete();
    }

    private function site_social_links_validate($data)
    {
        $validator = Validator::make($data, [
            'url' => 'required',
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }
    }
}
