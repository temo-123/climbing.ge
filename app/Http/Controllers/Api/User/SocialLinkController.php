<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Site_social_link;
use Validator;
use App\Services\SiteDataService;

class SocialLinkController extends Controller
{
    public function get_site_social_links()
    {
        return SiteDataService::getSocialLinks();
    }
    
    public function add_site_social_links(Request $request)
    {
        $data = $request->data;
        if (is_string($data)) {
            $data = json_decode($data, true);
        }
        $validate = $this->site_social_links_validate($data);

        if ($validate != null) {
            return($validate);
        }
        else{
            return SiteDataService::addSocialLink($data);
        }
    }

    public function del_site_social_links(Request $request)
    {
        return SiteDataService::deleteSocialLink($request->link_id);
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
