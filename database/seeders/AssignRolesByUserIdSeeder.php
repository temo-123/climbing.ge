<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\User\Role;

class AssignRolesByUserIdSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('slug', 'admin')->first();
        $userRole  = Role::where('slug', 'user')->first();

        if (!$adminRole || !$userRole) {
            $this->command->error('Required roles not found. Run RoleTableSeeder first.');
            return;
        }

        $adminAssigned = 0;
        $userAssigned  = 0;
        $skipped       = 0;

        User::orderBy('id')->chunk(200, function ($users) use (
            $adminRole, $userRole, &$adminAssigned, &$userAssigned, &$skipped
        ) {
            foreach ($users as $user) {
                if ($user->id === 1) {
                    $this->attachIfMissing($user->id, $adminRole->id)
                        ? $adminAssigned++
                        : $skipped++;
                } else {
                    $this->attachIfMissing($user->id, $userRole->id)
                        ? $userAssigned++
                        : $skipped++;
                }
            }
        });

        $this->command->info("Admin role assigned to user #1: " . ($adminAssigned ? 'yes' : 'already had it'));
        $this->command->info("User role assigned to {$userAssigned} user(s), {$skipped} skipped (already had it).");
    }

    private function attachIfMissing(int $userId, int $roleId): bool
    {
        $exists = DB::table('user_role')
            ->where('user_id', $userId)
            ->where('role_id', $roleId)
            ->exists();

        if ($exists) {
            return false;
        }

        DB::table('user_role')->insert([
            'user_id'    => $userId,
            'role_id'    => $roleId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return true;
    }
}
