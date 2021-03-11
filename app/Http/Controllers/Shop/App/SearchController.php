<?php

namespace App\Http\Controllers\Shop\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;

class SearchController extends Controller
{
    public function search(Request $request)
    {
		// From - https://justlaravel.com/search-functionality-laravel/
	    $q = $request['request'];
	    if ($q != "") {
		    $product = Product::where('published', '=', 1)->orWhere('short_description', 'LIKE', '%'.$q.'%')->get();
            
	        return view('shop.search', 
                        array(
                            'num_1' => 1,
                            'search' => 0
                            )
                        )
    	        
                        ->withOutdoors($product)
    	                ->withQuery($q);
	    } 	
	    else return view ( 'shop.search' )->withMessage ( 'No Details found. Try to search again !' );
    }


    public function index(Request $request)
    {
        $results = (new Search())
        ->registerModel(Article::class, ['title', 'short_description'])
        ->search($request->input('query'));
        
        return response()->json($results);
    }
}
