<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\User\Role;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clear existing user-role assignments
        DB::table('user_role')->delete();

        $baseUrl = config('app.base_url', 'example.com');

        // =============================
        // ADMIN USER
        // =============================
        $adminEmail = 'admin@' . $baseUrl;
        $admin = User::where('email', $adminEmail)->first();
        
        if (!$admin) {
            $admin = new User();
            $admin->name = 'admin';
            $admin->email = $adminEmail;
            $admin->password = Hash::make('secret');
            $admin->save();
            $this->command->info("Created admin user: {$adminEmail}");
        }

        $role_admin = Role::where('slug', 'admin')->first();
        if ($role_admin) {
            // Check if already attached to avoid duplicate
            if (!$admin->role()->where('role_id', $role_admin->id)->exists()) {
                $admin->role()->attach($role_admin);
                $this->command->info("Attached Admin role to user: {$adminEmail}");
            }
        } else {
            $this->command->error("Admin role not found!");
        }

        // =============================
        // DEFAULT USER
        // =============================
        $userEmail = 'user@' . $baseUrl;
        $user = User::where('email', $userEmail)->first();
        
        if (!$user) {
            $user = new User();
            $user->name = 'user';
            $user->email = $userEmail;
            $user->password = Hash::make('secret');
            $user->save();
            $this->command->info("Created user: {$userEmail}");
        }

        $role_user = Role::where('slug', 'user')->first();
        if ($role_user) {
            // Check if already attached to avoid duplicate
            if (!$user->role()->where('role_id', $role_user->id)->exists()) {
                $user->role()->attach($role_user);
                $this->command->info("Attached User role to user: {$userEmail}");
            }
        } else {
            $this->command->error("User role not found!");
        }

        // =============================
        // EXAMPLE: Assign roles to specific users by email
        // Uncomment and modify as needed
        // =============================

        /*
        // Manager users
        $managerEmails = [
            'manager@example.com',
            'content@example.com',
        ];

        $managerRole = Role::where('slug', 'content_menager')->first();
        
        foreach ($managerEmails as $email) {
            $managerUser = User::where('email', $email)->first();
            if ($managerUser && $managerRole) {
                if (!$managerUser->role()->where('role_id', $managerRole->id)->exists()) {
                    $managerUser->role()->attach($managerRole);
                    $this->command->info("Attached Content Manager role to: {$email}");
                }
            }
        }

        // Product managers
        $productManagerEmails = [
            'products@example.com',
        ];

        $productManagerRole = Role::where('slug', 'all_products_menager')->first();
        
        foreach ($productManagerEmails as $email) {
            $productUser = User::where('email', $email)->first();
            if ($productUser && $productManagerRole) {
                if (!$productUser->role()->where('role_id', $productManagerRole->id)->exists()) {
                    $productUser->role()->attach($productManagerRole);
                    $this->command->info("Attached Product Manager role to: {$email}");
                }
            }
        }

        // Tour managers
        $tourManagerEmails = [
            'tours@example.com',
        ];

        $tourManagerRole = Role::where('slug', 'all_tour_menager')->first();
        
        foreach ($tourManagerEmails as $email) {
            $tourUser = User::where('email', $email)->first();
            if ($tourUser && $tourManagerRole) {
                if (!$tourUser->role()->where('role_id', $tourManagerRole->id)->exists()) {
                    $tourUser->role()->attach($tourManagerRole);
                    $this->command->info("Attached Tour Manager role to: {$email}");
                }
            }
        }

        // Film managers
        $filmManagerEmails = [
            'films@example.com',
        ];

        $filmManagerRole = Role::where('slug', 'films_menager')->first();
        
        foreach ($filmManagerEmails as $email) {
            $filmUser = User::where('email', $email)->first();
            if ($filmUser && $filmManagerRole) {
                if (!$filmUser->role()->where('role_id', $filmManagerRole->id)->exists()) {
                    $filmUser->role()->attach($filmManagerRole);
                    $this->command->info("Attached Film Manager role to: {$email}");
                }
            }
        }

        // Order managers
        $orderManagerEmails = [
            'orders@example.com',
        ];

        $orderManagerRole = Role::where('slug', 'order_menager')->first();
        
        foreach ($orderManagerEmails as $email) {
            $orderUser = User::where('email', $email)->first();
            if ($orderUser && $orderManagerRole) {
                if (!$orderUser->role()->where('role_id', $orderManagerRole->id)->exists()) {
                    $orderUser->role()->attach($orderManagerRole);
                    $this->command->info("Attached Order Manager role to: {$email}");
                }
            }
        }

        // Team managers
        $teamManagerEmails = [
            'teams@example.com',
        ];

        $teamManagerRole = Role::where('slug', 'team_manager')->first();
        
        foreach ($teamManagerEmails as $email) {
            $teamUser = User::where('email', $email)->first();
            if ($teamUser && $teamManagerRole) {
                if (!$teamUser->role()->where('role_id', $teamManagerRole->id)->exists()) {
                    $teamUser->role()->attach($teamManagerRole);
                    $this->command->info("Attached Team Manager role to: {$email}");
                }
            }
        }
        */

        // =============================
        // EXAMPLE: Assign multiple roles to a user
        // =============================

        /*
        $multiRoleUser = User::where('email', 'multirole@example.com')->first();
        if ($multiRoleUser) {
            $role1 = Role::where('slug', 'content_menager')->first();
            $role2 = Role::where('slug', 'blog_menager')->first();
            
            if ($role1 && !$multiRoleUser->role()->where('role_id', $role1->id)->exists()) {
                $multiRoleUser->role()->attach($role1);
            }
            if ($role2 && !$multiRoleUser->role()->where('role_id', $role2->id)->exists()) {
                $multiRoleUser->role()->attach($role2);
            }
        }
        */
    }
}

