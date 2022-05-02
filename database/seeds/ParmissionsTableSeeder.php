<?php

use Illuminate\Database\Seeder;

use App\User;
use App\Models\Role;
use App\Models\Parmissions;

class ParmissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $admin = new Parmissions();
      $admin->parmision_name = 'admin';
      $admin->user_id = 1;
      $admin->role_id = 1;
      $admin->save();
    }
}
