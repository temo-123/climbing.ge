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
    public function get_user_feedbacks()
    {
        $user = auth()->user();
        $feedbacks = [];
        foreach ($user->product_feedbacks ?? [] as $feedback) {
            $feedbacks[] = [
                'feedback'       => $feedback,
                'locale_product' => $feedback->product->us_product,
                'global_product' => $feedback->product,
            ];
        }
        return $feedbacks;
    }

    public function get_all_feedbacks()
    {
        if ($auth = PermissionService::authorize('product_feedback', 'show')) return $auth;
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

    public function get_actyve_feedback($feedback_id)
    {
        return Product_feedback::where('id', strip_tags($feedback_id))->first();
    }

    public function get_product_feedbacks($product_id)
    {
        $feedbacks = Product_feedback::where('product_id', $product_id)->get();
        foreach ($feedbacks as $f) { $f->user; }
        return $feedbacks;
    }

    public function hide_feedback(Request $request, $feedback_id)
    {
        if ($auth = PermissionService::authorize('product_feedback', 'edit')) return $auth;
        $feedback = Product_feedback::find($feedback_id);
        if ($feedback) {
            $data = $request->input('data', []);
            $feedback->published = 0;
            $feedback->deleted_reason = $data['complaint'] ?? 'hidden';
            $feedback->save();
        }
        return response()->json(['success' => true]);
    }

    public function admin_hide_feedback(Request $request, $feedback_id)
    {
        if ($auth = PermissionService::authorize('product_feedback', 'edit')) return $auth;
        $feedback = Product_feedback::find($feedback_id);
        if ($feedback) {
            $feedback->published = 0;
            $feedback->admin_hidden = 1;
            $feedback->hidden_reason = $request->input('hidden_reason');
            $feedback->hidden_reason_text = $request->input('hidden_reason_text');
            $feedback->deleted_reason = $request->input('hidden_reason');
            $feedback->save();
        }
        return response()->json(['success' => true]);
    }

    public function user_hide_feedback($feedback_id)
    {
        $user = auth()->user();
        $feedback = $user->product_feedbacks()->find($feedback_id);
        if (!$feedback) return response()->json(['error' => 'Not found'], 404);
        if ($feedback->admin_hidden) return response()->json(['error' => 'Admin hidden', 'admin_hidden' => true], 403);
        $feedback->published = 0;
        $feedback->save();
        return response()->json(['success' => true]);
    }

    public function user_show_feedback($feedback_id)
    {
        $user = auth()->user();
        $feedback = $user->product_feedbacks()->find($feedback_id);
        if (!$feedback) return response()->json(['error' => 'Not found'], 404);
        if ($feedback->admin_hidden) return response()->json(['error' => 'Hidden by admin', 'admin_hidden' => true], 403);
        $feedback->published = 1;
        $feedback->save();
        return response()->json(['success' => true]);
    }

    public function edit_feedback(Request $request, $feedback_id)
    {
        $user = auth()->user();
        $hasAdminPermission = PermissionService::authorize('product_feedback', 'edit') === null;
        if ($hasAdminPermission) {
            $feedback = Product_feedback::find($feedback_id);
        } else {
            $feedback = $user->product_feedbacks()->find($feedback_id);
        }
        if (!$feedback) return response()->json(['error' => 'Not found'], 404);
        if (!$hasAdminPermission && $feedback->admin_hidden) {
            return response()->json(['error' => 'Admin hidden', 'admin_hidden' => true], 403);
        }
        if ($request->has('stars')) $feedback->stars = $request->input('stars');
        if ($request->has('text'))  $feedback->text  = $request->input('text');
        $feedback->save();
        return response()->json(['success' => true]);
    }

    public function del_feedback($id)
    {
        $auth = PermissionService::authorize('product_feedback', 'del');
        if ($auth) return $auth;

        return CommentService::del_comment($id, Product_feedback::class, User_product_feedbacks::class, 'feedback');
    }

    public function get_feedbacks_complaints(Request $request)
    {
        if ($auth = PermissionService::authorize('product_feedback', 'show')) return $auth;
        
        return Product_feedback_complaint::get();
    }

    public function make_decision(Request $request)
    {
        $auth = PermissionService::authorize('product_feedback', 'edit');
        if ($auth) return $auth;
        
        return CommentService::make_decision($request, Product_feedback::class, Product_feedback_complaint::class, Product::class, 'product', 'feedback');
    }
}

