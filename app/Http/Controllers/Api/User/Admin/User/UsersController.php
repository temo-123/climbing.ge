<?php

namespace App\Http\Controllers\Api\User\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator;
use Auth;
use Mail;
use Hash;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\User\Role;
use App\Models\User\Permission;
use App\Models\User\user_notification;

use App\Models\User\User_role;
use App\Models\User\User_permission;

use App\Services\Abstract\ImageControllService;
use App\Services\PermissionService;
use App\Services\AdminUserOverviewService;

class UsersController extends Controller
{
    public function get_all_users()
    {
        $auth = PermissionService::authorize('user', 'show');
        if ($auth) return $auth;
        return User::with(['role', 'partner_organization_member.organization'])->withPointsCounts()->latest('id')->get()->map(function ($user) {
            $user->is_banned = $user->isBanned();
            $user->role_name = optional($user->role->first())->name;
            $user->partner_organization_name = optional($user->partner_organization_member->organization ?? null)->name;
            $user->points_total = User::pointsFromCounts($user);
            $user->activity_status = $this->activity_status($user->last_seen_at);
            $user->age = $user->birthday ? \Carbon\Carbon::parse($user->birthday)->age : null;
            return $user;
        });
    }

    /**
     * Bucket a user's last_seen_at into the options the admin Users list
     * activity filter offers.
     */
    private function activity_status($lastSeenAt): string
    {
        if (!$lastSeenAt) return 'inactive';
        if ($lastSeenAt->diffInMinutes(now()) <= 15) return 'online';
        if ($lastSeenAt->diffInDays(now()) <= 7) return 'recent';
        return 'inactive';
    }

    public function get_auth_user_data() {
        return auth()->user()->first();
    }

    public function get_user_data(Request $request) {
        $auth = PermissionService::authorize('user', 'show');
        if ($auth) return $auth;
        return user::where('id', '=', $request->user_id)->first();
    }

    /**
     * Read-only "everything about this user" snapshot for the admin Users list
     * info modal — see AdminUserOverviewService.
     */
    public function get_user_overview($user_id) {
        $auth = PermissionService::authorize('user', 'show');
        if ($auth) return $auth;

        $overview = AdminUserOverviewService::build((int) $user_id);
        if (!$overview) return response()->json(['message' => 'User not found.'], 404);

        return response()->json($overview);
    }

    public function get_worker_users() {
        $auth = PermissionService::authorize('user', 'show');
        if ($auth) return $auth;
        $all_users = User::get();
        $prmishened_users = [];

        foreach ($all_users as $user) {
            $user_perms = $user->permissions;
            // dd($user_perms);
            foreach ($user_perms as $user_perm) {
                if ($user_perm['subject'] == 'worker') {
                    array_push($prmishened_users, $user);
                }
            }
        }

        return $prmishened_users;
    }

    public function get_team_members() {
        $auth = PermissionService::authorize('user', 'show');
        if ($auth) return $auth;
        return User::where('is_team_member', '!=', null)->get();
    }

    // Users list → "Add user" modal
    public function create_user_by_admin(Request $request)
    {
        $auth = PermissionService::authorize('user', 'add');
        if ($auth) return $auth;

        $data = $request->input('data', $request->all());
        $validator = Validator::make($data, [
            'name' => 'required|string|max:25',
            'surname' => 'required|string|max:55',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'birthday' => 'nullable|date|before:today|after:1900-01-01',
            'phone_number' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'role_id' => 'nullable|integer|exists:roles,id',
            'verified' => 'nullable|boolean',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'errors' => $validator->errors()], 422);
        }

        // giving the admin role needs role › edit
        if (!empty($data['role_id']) && DB::table('roles')->where('id', $data['role_id'])->value('slug') === 'admin' && !Auth::user()->hasPermissionFor('role', 'edit')) {
            return response()->json(['message' => 'You cannot give the admin role.'], 403);
        }

        $new_user = new User();
        $new_user->name = $data['name'];
        $new_user->surname = $data['surname'];
        $new_user->email = trim($data['email']);
        $new_user->password = bcrypt($data['password']);
        $new_user->birthday = $data['birthday'] ?? null;
        $new_user->phone_number = $data['phone_number'] ?? null;
        $new_user->country = $data['country'] ?? null;
        $new_user->city = $data['city'] ?? null;
        if (!empty($data['verified'])) $new_user->email_verified_at = now();
        $new_user->save();

        $this->create_user_notifications($new_user->id);
        $this->create_user_permissions($new_user->id);
        if (!empty($data['role_id'])) {
            DB::table('user_role')->where('user_id', $new_user->id)->delete();
            DB::table('user_role')->insert(['user_id' => $new_user->id, 'role_id' => (int) $data['role_id'], 'created_at' => now(), 'updated_at' => now()]);
        }

        return response()->json(['success' => true, 'user' => $new_user->only(['id', 'name', 'surname', 'email'])], 201);
    }

    public function update_password(Request $request)
    {
        #Match The Old Password
        if(!Hash::check($request['data']['old_pass'], auth()->user()->password)){
            return "Old Password Doesn't match!";
        }

        #Update the new Password
        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request['data']['new_pass'])
        ]);

        return "Password changed successfully!";
    }


    public function get_auth_user_permissions(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json('Plees login', 401);
        }

        return $user->getAllPermissions()->map(fn($p) => [
            'action'  => $p->action,
            'subject' => $p->subject,
        ])->values();
    }
    public function user_image_update(Request $request)
    {
        if (Auth::user()) {
            $user = user::where('id', '=', $request->user_id)->first();
            if($user->image != null){
                if($request->hasFile('image')){
                    $file_new_name = ImageControllService::image_update('images/user_profil_img/', $user, $request, 'image', 'image');
                    $user['image'] = $file_new_name;
                }
        
                $user -> save();
            }
            else{
                if($request->hasFile('image')){
                    $file_new_name = ImageControllService::image_upload('images/user_profil_img/', $request, 'image');
                    $user['image'] = $file_new_name;
                }
        
                $user -> save();
            }
        }
        else{
            return 'Plees login';
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    private function create_user_permissions(int $user_id)
    {
        if(User_role::where('user_id', '=', $user_id)->count() == 0){
            $new_permission_item =  new User_role();

            $new_permission_item['user_id'] = $user_id;
            $new_permission_item['role_id'] = Role::where('slug', '=', 'user')->first()->id; // ID 1 in role tab is a user

            $new_permission_item -> save();
        }
    }

    public function create_user_notifications($user_id)
    {
        // notification_type has been a single JSON column since the
        // 2025_10_25_173638 migration dropped the individual per-preference
        // columns this used to set directly (add_new_gym, news, etc.) — those
        // no longer exist, so setting them here would throw an "unknown
        // column" SQL error. firstOrCreate() also guards against duplicate
        // rows for the same user_id, which the old unconditional `new` + save()
        // didn't.
        user_notification::firstOrCreate(
            ['user_id' => $user_id],
            ['notification_type' => [
                'add_new_gym' => true,
                'news' => true,
                'add_new_ice_spot' => true,
                'add_new_outdoor_spot' => true,
                'add_new_product' => true,
                'add_new_sector' => true,
                'add_new_service' => true,
                'add_new_techtip' => true,
                'favorite_film' => true,
                'favorite_outdoor' => true,
                'favorite_product' => true,
                'interested_event' => true,
            ]]
        );
    }

    public function reset_password(Request $request, $user_id)
    {
        $auth = PermissionService::authorize('user', 'edit');
        if ($auth) return $auth;

        $user = User::findOrFail($user_id);
        $user->password = bcrypt($request->password);
        $user->save();
        $user->tokens()->delete();

        return response()->json(['message' => 'Password has been reset.']);
    }

    /**
     * Admin edit of a user's profile fields (name, contacts, location, bio)
     * from the Manage-user modal on the admin Users list.
     */
    public function update_user_info(Request $request, $user_id)
    {
        $auth = PermissionService::authorize('user', 'edit');
        if ($auth) return $auth;

        $user = User::findOrFail($user_id);

        $validator = Validator::make($request->all(), [
            'name'         => 'required|string|max:255',
            'surname'      => 'required|string|max:255',
            'email'        => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:50',
            'country'      => 'nullable|string|max:255',
            'city'         => 'nullable|string|max:255',
            'my_bio'       => 'nullable|string|max:5000',
            'birthday'     => 'nullable|date|before:today|after:1900-01-01',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        // Changing the e-mail invalidates the old verification.
        $email_changed = strcasecmp($data['email'], (string) $user->email) !== 0;
        if ($email_changed) {
            $user->email_verified_at = null;
        }

        $user->fill($data);
        $user->save();

        if ($email_changed) {
            try {
                $user->sendEmailVerificationNotification();
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return response()->json(['message' => 'User updated.', 'user' => $user->only([
            'id', 'name', 'surname', 'email', 'phone_number', 'country', 'city', 'my_bio', 'birthday', 'email_verified_at',
        ])]);
    }

    public function verify_user(Request $request, $user_id)
    {
        $auth = PermissionService::authorize('user', 'edit');
        if ($auth) return $auth;

        $user = User::findOrFail($user_id);
        if (is_null($user->email_verified_at)) {
            $user->email_verified_at = now();
            $user->save();
        }

        return response()->json(['email_verified_at' => $user->email_verified_at]);
    }

    // Send the user a fresh verification e-mail (Manage user › Profile)
    public function resend_verification(Request $request, $user_id)
    {
        $auth = PermissionService::authorize('user', 'edit');
        if ($auth) return $auth;

        $user = User::findOrFail($user_id);
        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified', 'email_verified_at' => $user->email_verified_at]);
        }

        try {
            $user->sendEmailVerificationNotification();
        } catch (\Throwable $e) {
            report($e);
            return response()->json(['message' => 'Could not send verification email.'], 500);
        }

        return response()->json(['message' => 'Verification email sent']);
    }

    public function del_user($user_id)
    {
        $auth = PermissionService::authorize('user', 'del');
        if ($auth) return $auth;
        $deliting_user = User::where('id', $user_id)->first();

        if (Auth::user()->id != $deliting_user->id) {
            if(User_role::where('user_id', '=', $deliting_user->id)->count() > 0){
                $user_roles = User_role::where('user_id', '=', $deliting_user->id)->first();
                $user_roles -> delete();
            }
    
            if(user_notification::where('user_id', '=', $deliting_user->id)->count() > 0){
                $user_notifications = user_notification::where('user_id', '=', $deliting_user->id)->first();
                $user_notifications -> delete();
            }
    
            if(User_permission::where('user_id', '=', $deliting_user->id)->count() > 0){
                $user_parmissions = User_permission::where('user_id', '=', $deliting_user->id)->get();
    
                foreach ($user_parmissions as $user_parmission) {
                    $user_parmission -> delete();
                }
            }

            if ($deliting_user->image) {
                ImageControllService::image_delete('images/user_profil_img/'.$deliting_user->image.'_img/', $deliting_user, 'image');
            }

            $deliting_user ->delete();

            return("User deleted socsesful");
        }
        else {
            return("You don`t can delete yourself! :)");
        }
    }

    public function bulk_delete(Request $request)
    {
        $auth = PermissionService::authorize('user', 'del');
        if ($auth) return $auth;

        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer',
        ]);

        // Never allow an admin to bulk-delete their own account out of their session.
        $ids = array_diff($request->ids, [auth()->id()]);

        if (empty($ids)) {
            return response()->json(['success' => true, 'count' => 0]);
        }

        DB::transaction(function () use ($ids) {
            foreach ($ids as $id) {
                $deliting_user = User::find($id);
                if (!$deliting_user) continue;

                if (User_role::where('user_id', '=', $deliting_user->id)->count() > 0) {
                    $user_roles = User_role::where('user_id', '=', $deliting_user->id)->first();
                    $user_roles->delete();
                }

                if (user_notification::where('user_id', '=', $deliting_user->id)->count() > 0) {
                    $user_notifications = user_notification::where('user_id', '=', $deliting_user->id)->first();
                    $user_notifications->delete();
                }

                if (User_permission::where('user_id', '=', $deliting_user->id)->count() > 0) {
                    $user_parmissions = User_permission::where('user_id', '=', $deliting_user->id)->get();
                    foreach ($user_parmissions as $user_parmission) {
                        $user_parmission->delete();
                    }
                }

                if ($deliting_user->image) {
                    ImageControllService::image_delete('images/user_profil_img/'.$deliting_user->image.'_img/', $deliting_user, 'image');
                }

                $deliting_user->delete();
            }
        });

        return response()->json(['success' => true, 'count' => count($ids)]);
    }
}
