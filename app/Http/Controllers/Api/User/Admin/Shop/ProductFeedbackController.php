<?php

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;

use App\Models\User;
use App\Models\Shop\Product;
use App\Models\Shop\Product_feedback;
use App\Models\Shop\User_product_feedbacks;
use App\Models\Shop\Product_feedback_complaint;
use App\Models\Shop\Product_feedback_query;

use App\Services\CommentService;

class ProductFeedbackController extends Controller
{
    public function get_all_feedbacks()
    {
        if(Product_feedback::count() > 0){
            $all_feedbacks = Product_feedback::get();
            
            $feedbacks = [];
            foreach ($all_feedbacks as $feedback) {
                array_push($feedbacks, [
                    'feedback' => $feedback, 
                    'locale_product' => $feedback->product->us_product,
                    'global_product' => $feedback->product
                ]);
            }
            
            return $feedbacks;
        }
    }

    public function get_user_feedbacks()
    {
        $user_id = auth()->user()->id;

        if(user::where("id", "=", $user_id)->count() > 0){
            $user = auth()->user();
            
            if($user->product_feedbacks != null){
                $user_feedbacks = $user->product_feedbacks;
                $feedbacks = [];
                foreach ($user_feedbacks as $feedback) {
                    
                    array_push($feedbacks, [
                        'feedback' => $feedback, 
                        'locale_product' => $feedback->product->us_product,
                        'global_product' => $feedback->product
                    ]);
                }
                
                return $feedbacks;
            }
        }
    }

    public function get_product_feedbacks(Request $request)
    {
        $feedbacks = Product_feedback::where("product_id", '=', $request->product_id)->where("deleted_reason", '=', null)->where("published", '=', 1)->latest()->get();
        $feedback_array = [];

        foreach ($feedbacks as $feedback) {
            array_push($feedback_array, ['feedback' => $feedback, 'user' => $feedback->user->first()]);
        }

        return $feedback_array;
    }

    public function create_feedback(Request $request)
    {
        $user = Product_feedback::where("email", "=", $request['data']['email'])->count();

        if($user == 0){
            return CommentService::create_comment($request, Product_feedback::class, User_product_feedbacks::class, 'product', 'feedback');
            // return 'Thenks for your feedback!';
        }
        else{
            return "You already have feedback for this product! You don't can add another feedback!";
        }
    }

    public function del_feedback($id)
    {
        return CommentService::del_comment($id, Product_feedback::class, User_product_feedbacks::class, 'feedback');
    }

    public function hide_feedback(Request $request)
    {
        $data = $request['data'];

        $actyve_feedback = Product_feedback::where("id", '=', $data['comment_id'])->first();

        return CommentService::comment_hide($data['complaint'], date("Y-m-d H:I:s"), $data['email'], $actyve_feedback->id, $data['comment_id'], Product_feedback::class, Product_feedback::class, 'product', 'feedback');
    }

    public function add_feedback_complaint(Request $request)
    {
        return CommentService::add_complaint($request, Product_feedback_complaint::class, 'product', 'feedback');
    }
    
    public function get_actyve_feedback(Request $request)
    {
        return Product_feedback::where('id',strip_tags($request->feedback_id))->first();
    }

    public function get_feedbacks_complaints(Request $request)
    {
        return Product_feedback_complaint::get();
    }

    public function make_decision(Request $request)
    {
        return CommentService::make_decision($request, Feedback::class, Product_feedback_complaint::class, Product::class, 'product', 'feedback');
    }

    public function confirm_email(Request $request) 
    {        
        return CommentService::confirm_email($request->email);
    }
}
