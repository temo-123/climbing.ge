<?php

namespace App\Http\Controllers\Site\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// use Illuminate\Support\Facades\Input;
use App\Models\Article;
use Spatie\Searchable\Search;

class SearchController extends Controller
{
    public function search(Request $request)
    {
		// From - https://justlaravel.com/search-functionality-laravel/
		// dd($request['request']);
	    $q = $request['request'];
	    if ($q != "") {
		    // $outdoor = Article::where('published', '=', 1)->where('category', '=', 'outdoor')->orWhere('short_description', 'LIKE', '%'.$q.'%')->get();
		    $outdoor = Article::where('published', '=', 1)->where('category', '=', 'outdoor')->get();
		    $indoor = Article::where('published', '=', 1)->where('category', '=', 'indoor')->get();
		    $ice = Article::where('published', '=', 1)->where('category', '=', 'ice')->get();
		    $other = Article::where('published', '=', 1)->where('category', '=', 'other')->get();
		    $mount = Article::where('published', '=', 1)->where('category', '=', 'mount_route')->get();

		    $event = Article::where('published', '=', 1)->where('completed', '=', 0)->where('category', '=', 'event')->get();
            
	        return view('site.search', 
                        array(
                            'num_1' => 1,
                            'num_2' => 1,
                            'num_3' => 1,
                            'num_4' => 1,
                            'num_5' => 1,
                            'search' => 0
                            )
                        )
    	        
                        ->withIndoors($indoor)
                        ->withOutdoors($outdoor)
                        ->withMounts($mount)
                        ->withIces($ice)
                        ->withOthers($other)
                        ->withEvents($event)
    	                ->withQuery($q);
	    } 	
	    else return view ( 'site.search' )->withMessage ( 'No Details found. Try to search again !' );
    }


    public function index(Request $request)
    {
        $results = (new Search())
        ->registerModel(Article::class, ['title', 'short_description'])
        ->search($request->input('query'));
        
        return response()->json($results);
    }
}
