<?php

namespace App\Http\Controllers\Api\User\Admin\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User\Non_registered_commenter;
use App\Services\PermissionService;

class NonRegisteredCommenterController extends Controller
{
    public function get_non_registered_commenter() {
        // $auth = PermissionService::authorize('commenter', 'del');
        // if ($auth) return $auth;
        
        $comenters = Non_registered_commenter::count();

        if($comenters != null){
            $comenters = Non_registered_commenter::get();
            $return = [];
            foreach ($comenters as $comenter) {
                array_push($return, [
                    'comenter' => $comenter, 
                    'product_comments' => $comenter->product_feedbacks->count(),
                    'article_comments' => $comenter->article_comments->count()
                ]);
            }
            
            return $return;
        }
    }

    public function del_non_registered_commenter($id) {
        $auth = PermissionService::authorize('commenter', 'del');
        if ($auth) return $auth;
        
        $del_non_registered_commenter = Non_registered_commenter::where("id", "=", $id)->first();
        $del_non_registered_commenter -> delete();
    }
}
