<?php

namespace App\Http\Controllers\Api\User\Admin\Shop;

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
use App\Services\PermissionService;

class ProductFeedbackController extends Controller
{
    public function get_all_feedbacks()
    {
        if (Product_feedback::count() > 0) {
            $all_feedbacks = Product_feedback::get();
            $feedbacks = [];
            foreach ($all_feedbacks as $feedback) {
                array_push($feedbacks, [
                    'feedback'       => $feedback,
                    'locale_product' => $feedback->product->us_product,
                    'global_product' => $feedback->product,
                ]);
            }
            return $feedbacks;
        }
        return [];
    }

    public function del_feedback($id)
    {
        $auth = PermissionService::authorize('product_feedback', 'del');
        if ($auth) return $auth;
        
        return CommentService::del_comment($id, Product_feedback::class, User_product_feedbacks::class, 'feedback');
    }

    public function get_feedbacks_complaints(Request $request)
    {
        $auth = PermissionService::authorize('product_feedback', 'edit');
        if ($auth) return $auth;
        
        return Product_feedback_complaint::get();
    }

    public function make_decision(Request $request)
    {
        $auth = PermissionService::authorize('product_feedback', 'edit');
        if ($auth) return $auth;
        
        return CommentService::make_decision($request, Feedback::class, Product_feedback_complaint::class, Product::class, 'product', 'feedback');
    }
}

