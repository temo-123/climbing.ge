<?php

namespace App\Services;

use Auth;
use Validator;
use Notification;

use App\Models\User;
use App\Models\Site;
use App\Models\Role;
use App\Models\User_role;
use App\Models\User\Non_registered_commenter;

use App\Notifications\comments\HideCommentNotification;
use App\Notifications\comments\CommentComplaintDecisionToDecisionerNotification;
use App\Notifications\comments\AdminComplaintNotification;
use App\Notifications\comments\CommentEmailVarificationNotification;

class CommentService
{   
    public static function create_comment($request, $comment_model, $comment_user_relation_modal, $prefix, $relatione_prefix)
    {
        /*
        *
        * This function upload 1 file
        *
        * Function get 5 parameters
        *
        * $image_dir            - image wai in derectory from '/public/' derectory
        * $request              - HTTP request
        * $form_value_id        - image value name in your form
        * $resize               - Image resize action (defolt it null)
        * $save_origin_image    - Save orifinal image in ./origin_img/ folder (defolt it true)
        *
        */

        $validate = (new static)->comment_validate($request);
        
        $is_verify_isset = $request->data['is_verify_isset'];
        
        if($is_verify_isset && !$validate){
            $data = $request->data;

            if (Auth::user()) {
                if(Auth::user()->role[0]['slug'] == 'ban'){
                    return 'Your profile has been blocked. You cannot add a comment.';
                }
            }
            else{
                $validate_num = (new static)->check_non_registred_emails_violation($data['email'], $prefix, $relatione_prefix);
                if($validate_num >= 5){
                    return 'You have violated the terms of use of the site more than 5 times. You can no longer post comments';
                }
            }

            unset($data['is_verify_isset']);

            $data[$prefix.'_id'] = $request[$prefix.'_id'];

            if (Auth::user()) {
                $data['published'] = 1;
            }
            
            $activ_id = $comment_model::insertGetId($data);

            if (Auth::user()) {
                // if user registred & authed created relation
                $comment_article = new $comment_user_relation_modal;
                $comment_article[$relatione_prefix.'_id'] = $activ_id;
                $comment_article['user_id'] = Auth::user()->id;
                $comment_article->save();

                return ([
                    "message" => "Tenk you for ".$prefix." ".$request->name,
                    "new_comment_id" => $activ_id
                ]);
            }
            else if (!Auth::user()) {
                $user_count = User::where('email', '=', $data['email'])->count();

                if($user_count != 0){
                    // if user registred but not authed created relation
                    $user = User::where('email', '=', $data['email'])->first();

                    $new_comment = $comment_model::where('id', '=', $activ_id)->first();
                    $new_comment['published'] = 1;
                    $new_comment->save();

                    $comment_article = new $comment_user_relation_modal;
                    $comment_article[$relatione_prefix.'_id'] = $activ_id;
                    $comment_article['user_id'] = $user->id;
                    $comment_article->save();

                    // return ("Tenk you for comment ".$request->name);
                    return ([
                        "message" => "Tenk you for ".$prefix." ".$request->name,
                        "new_comment_id" => $activ_id
                    ]);
                }
                else{
                    // if user not registred & not authed created non_registered_commenters item
                    // return (new static)->created_non_registered_commenter_relation($data['email'], $activ_id, $comment_model);
                    return ([
                        "message" => (new static)->created_non_registered_commenter_relation($data['email'], $activ_id, $comment_model),
                        "new_comment_id" => $activ_id
                    ]);
                }
            }
        }
        else{
            return ("Please reload page and add comment after pdating!");
        }
    }

    public static function del_comment($id, $comment_model, $comment_user_relation, $prefix)
    {
        /*
        *
        * This function upload 1 file
        *
        * Function get 5 parameters
        *
        * $image_dir            - image wai in derectory from '/public/' derectory
        * $request              - HTTP request
        * $form_value_id        - image value name in your form
        * $resize               - Image resize action (defolt it null)
        * $save_origin_image    - Save orifinal image in ./origin_img/ folder (defolt it true)
        *
        */
        $comment = $comment_model::where('id',strip_tags($id))->first();
        $comment_user_count = $comment_user_relation::where($prefix.'_id', '=', $comment->id)->count();
        if($comment_user_count > 0){
            $comment_user = $comment_user_relation::where($prefix.'_id', '=', $comment->id)->first();
            $comment_user ->delete();
        }

        $comment ->delete();
    }

    public static function add_complaint($request, $complaint_model, $query, $prefix)
    {
        /*
        *
        * This function upload 1 file
        *
        * Function get 5 parameters
        *
        * $image_dir            - image wai in derectory from '/public/' derectory
        * $request              - HTTP request
        * $form_value_id        - image value name in your form
        * $resize               - Image resize action (defolt it null)
        * $save_origin_image    - Save orifinal image in ./origin_img/ folder (defolt it true)
        *
        */
        if (Auth::user()) {
            $user_complaint_count = $complaint_model::where('user_id', '=', Auth::user())->where($prefix.'_id', '=', $request[$prefix.'_id'])->count();

            if($user_complaint_count){
                return('You olrede have complaint on this comment!');
            }
            else{
                $comment_complaint = new $complaint_model;
        
                $comment_complaint[$prefix.'_id'] = $request[$prefix.'_id'];
                $comment_complaint->complaint = $request[$prefix.'_complaint'];
                $comment_complaint->user_id = Auth::user()->id;
                
                $comment_complaint->save();

                if($request[$prefix.'_complaint'] == "This is my comment"){
                    $query = $query::where($prefix.'_id', '=', $request[$prefix.'_id'])->first();
                    $query -> delete();
                }

                (new static)->admin_complaint_notification();

                return('Thank you! We will look into your complaint and let you know!');
            }
        }
        else{
            return('Plees login');
        }
    }

    public static function make_decision($request, $comment_model, $complaint_model, $commented_model, $commented_model_prefic, $prefix)
    {
        /*
        *
        * This function upload 1 file
        *
        * Function get 5 parameters
        *
        * $image_dir            - image wai in derectory from '/public/' derectory
        * $request              - HTTP request
        * $form_value_id        - image value name in your form
        * $resize               - Image resize action (defolt it null)
        * $save_origin_image    - Save orifinal image in ./origin_img/ folder (defolt it true)
        *
        */
        $comment = $comment_model::where('id', '=', $request->comment_id)->first();
        
        $complaint = $complaint_model::where('id', '=', $request->complaint_id)->first();
        $complaint_user = User::where('id', '=', $complaint->user_id)->first();

        if($request->decision == 'approve_request'){

            $comment['deleted_reason'] = $complaint->complaint;
            $comment['deleted_at'] =  date("Y-m-d H:I:s");

            (new static)->comment_hide(
                $complaint->complaint, 
                $comment['deleted_at'], 
                $comment->email, 
                $comment[$commented_model_prefic.'_id'],
                $comment->id,
                $comment_model, 
                $commented_model, 
                $commented_model_prefic,
                $prefix
            );
            (new static)->comment_complaint_decision_to_decisione_notification(
                $request->decision, 
                $complaint->complaint,
                $complaint_user->email, 
            );

            $complaint->delete();

            return 'approve_request';
        }
        else if($request->decision == 'reject_request'){
            (new static)->comment_complaint_decision_to_decisione_notification(
                $request->decision, 
                $complaint->complaint,
                $complaint_user->email, 
            );
            $complaint->delete();

            return 'reject_request';
        }
    }

    public static function comment_hide($cause, $data_time, $email, $commented_id, $comment_id, $comment_model, $commented_model, $commented_model_prefic, $prefix)
    {   
        /*
        *
        * This function upload 1 file
        *
        * Function get 5 parameters
        *
        * $image_dir            - image wai in derectory from '/public/' derectory
        * $request              - HTTP request
        * $form_value_id        - image value name in your form
        * $resize               - Image resize action (defolt it null)
        * $save_origin_image    - Save orifinal image in ./origin_img/ folder (defolt it true)
        *
        */
        $violation_count = (new static)->check_user_violation($email, $prefix, $commented_model_prefic);

        // create user ban
        if($violation_count > 5){
            $user = User::where('email', '=', $email)->first();
            $ban_role = Role::where("slug", "=", 'ban')->first();

            $user_role = User_role::where("user_id", "=", $user->id)->first();
            $user_role['role_id'] = $ban_role->id;
            $user_role->save();
        }

        $comment = $comment_model::where('id', '=', $comment_id)->first();
        // dd($comment);
        $comment['deleted_reason'] = $cause;
        $comment['deleted_at'] =  date("Y-m-d H:I:s");
        // $comment->softDeletes();
        // dd($comment);
        $comment->save();

        // $comented = $commented_model::where('id', '=', $id)->first();
        $comented = $comment->$commented_model_prefic;
        $actyve_pref = 'us_'.$commented_model_prefic;
        $us_comented = $comented->$actyve_pref;

        $info = array(  
            'cause' => $cause,
            'data_time' => $data_time,
            'comented_title' => $us_comented->title,
            'violation_count' => $violation_count,
        );

        Notification::route('mail', $email)->notify(new HideCommentNotification($info));
    }

    private static function comment_complaint_decision_to_decisione_notification($cause, $decision, $email)
    {
        $info = array(  
            'cause' => $cause,
            'decision' => $decision,
        );
        Notification::route('mail', $email)->notify(new CommentComplaintDecisionToDecisionerNotification($info));
    }

    private static function admin_complaint_notification()
    {
        $site_data = Site::first();

        Notification::route('mail', $site_data->email)->notify(new AdminComplaintNotification());
    }

    private static function created_non_registered_commenter_relation($email, $id, $comment_model,){
        $non_registered_commenter_count = Non_registered_commenter::where('email', '=', $email)->count();
        $non_registered_commenter = Non_registered_commenter::where('email', '=', $email)->first();

        if($non_registered_commenter_count == 0 || $non_registered_commenter->confirmed == 0){
            $non_registered_commenter = new Non_registered_commenter;
            $non_registered_commenter['email'] = $email;
            $non_registered_commenter->save();

            return (new static)->varificate_email($email, $id);
        }
        else if ($non_registered_commenter->confirmed == 1) {
            $comm = $comment_model::where('id', '=', $id)->first();
            $comm['published'] = 1;
            $comm -> save();

            return ("Tenk you for comment ".$comm->name);
        }
        
    }

    private static function varificate_email($email, $id) {
        $info = [
            "host" => $_SERVER['HTTP_HOST'],
            "id" => $id,
            "user_email" => $email
        ];
        Notification::route('mail', $email)->notify(new CommentEmailVarificationNotification($info));

        return ("Tenk you for comment. Please go to your email to confirm it. Your comment will not appear until you do this!");
    }

    public static function confirm_email($email) {
        /*
        *
        * This function upload 1 file
        *
        * Function get 5 parameters
        *
        * $image_dir            - image wai in derectory from '/public/' derectory
        * $request              - HTTP request
        * $form_value_id        - image value name in your form
        * $resize               - Image resize action (defolt it null)
        * $save_origin_image    - Save orifinal image in ./origin_img/ folder (defolt it true)
        *
        */
        
        $em = Non_registered_commenter::where('email', '=', $email)->first();
        $em['confirmed'] = 1;
        $em -> save();

        foreach ($em->article_comments as $art_comm) {
            $art_comm['published'] = 1;
            $art_comm -> save();
        }
        foreach ($em->product_feedbacks as $prod_comm) {
            $prod_comm['published'] = 1;
            $prod_comm -> save();
        }
    }


    /*
    *
    * Check violation
    *
    */
    private static function check_user_violation($email, $prefix, $relatione_prefix){
        if(User::where('email', '=', $email)->count() > 0){
            $user = User::where('email', '=', $email)->first();
            $pref = $relatione_prefix.'_'.$prefix.'s';
            
            $user_comments_count = $user->$pref->count();

            if($user_comments_count > 0){
                $user_comments = $user->$pref;
                return $user_comments->where('deleted_reason', '!=', null)->count();
            }
        }
        return 0;
    }

    private static function check_non_registred_emails_violation($email, $prefix, $relatione_prefix){
        if(Non_registered_commenter::where('email', '=', $email)->count() > 0){
            $comenter = Non_registered_commenter::where('email', '=', $email)->first();
            $pref = $prefix.'_'.$relatione_prefix.'s';
            $comenter_comments_count = $comenter->$pref->count();

            if($comenter_comments_count > 0){
                $comenter_comments = $comenter->$pref;
                return $comenter_comments->where('deleted_reason', '!=', null)->count();
            }
        }
        return 0;
    }

    /*
    *
    * Validation
    *
    */
    private static function comment_validate($request)
    {
        $data = $request->validate([
            'data.name' => 'required|max:25',
            'data.surname' => 'required|max:55',
            'data.email' => 'required|email',
            'data.text' => 'required',
        ]);
    }
}
