<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Comment;
use Auth;

class CommentsController extends Controller
{
    public function index(Request $request)
    {
        $request->user()->authorizeRoles(['manager', 'admin']);
        
	    $comments = Comment::get();
	    
		$my_comments = Comment::where('user_id', '=', Auth::user()->id)->get();
        
        if (view()->exists('user.comments_list')) {
    	   
    		$data = [
                'title'=>'comments',
                
    			'table_1'=>$comments,
    		    'table_1_name' => 'All Comments',
    		    'table_1_add_url'=>'sectorAdd',
    		    'table_1_edit_url'=>'sectorEdit',
                
                'table_2' => $my_comments,
    		    'table_2_name' => 'My Comments',
    		    'table_2_add_url'=>'routeAdd',
    		    'table_2_edit_url'=>'routeEdit',

    		    'page_name' => 'Comments',
    		    'active' => 'mount',
    		];
    		return view('user.comments_list',$data);
    	}
    	abort(404);
    }
}
