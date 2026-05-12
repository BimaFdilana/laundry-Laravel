<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        $user = User::create([
            'name' => 'Super Administrator',
            'email' => 'superadmin@laundry.com',
            'status' => 'Active',
            'auth' => 'SuperAdmin',
            'password' => bcrypt('123456')
        ]);

        $role = Role::create(['name' => 'SuperAdmin']);
        $user->assignRole('SuperAdmin');
    }
}
