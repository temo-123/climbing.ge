<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\User\Role;

class AssignUserRoleToAllSeeder extends Seeder
{
    public function run(): void
    {
        $userRole = Role::where('slug', 'user')->first();

        if (!$userRole) {
            $this->command->error('Role with slug "user" not found. Run the SQL import first.');
            return;
        }

        $users = User::all();
        $assigned = 0;
        $skipped = 0;

        foreach ($users as $user) {
            $alreadyHas = DB::table('user_role')
                ->where('user_id', $user->id)
                ->where('role_id', $userRole->id)
                ->exists();

            if (!$alreadyHas) {
                DB::table('user_role')->insert([
                    'user_id' => $user->id,
                    'role_id' => $userRole->id,
                ]);
                $assigned++;
            } else {
                $skipped++;
            }
        }

        $this->command->info("Done: {$assigned} users assigned the 'user' role, {$skipped} already had it.");
    }
}
