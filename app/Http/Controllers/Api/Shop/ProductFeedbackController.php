<?php

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Product;
use App\Models\Product_feedback;
// use App\Models\Article_Feedback_complaint;
// use App\Models\Article_Feedback_query;

class ProductFeedbackController extends Controller
{
    public function get_all_Feedbacks()
    {
        return Product_feedback::get();
    }

    public function get_user_Feedbacks()
    {
        $user_id = auth()->user()->id;

        $user = user::where("id", "=", $user_id)->first();
        return $user->Feedbacks;
    }

    public function get_product_Feedbacks(Request $request)
    {
        $Feedbacks = Product_feedback::where("product_id", '=', $request->product_id)->where("deleted_reason", '=', null)->latest()->get();
        $Feedback_array = [];

        foreach ($Feedbacks as $Feedback) {
            if($Feedback->user->first() != []){
                array_push($Feedback_array, ["Feedback" => $Feedback, "user" => $Feedback->user->first()]);
            }
            else {
                array_push($Feedback_array, ["Feedback" => $Feedback]);
            }
        }

        return $Feedback_array;
    }

    public function create_Feedback(Request $request)
    {
        FeedbackService::create_Feedback($request, Product_Feedbacks::class, Article_Feedback_user::class, Article_Feedback_query::class, 'article');
    }

    public function del_Feedback($id)
    {
        FeedbackService::del_Feedback($id, Product_Feedbacks::class, Article_Feedback_user::class);
    }
}
