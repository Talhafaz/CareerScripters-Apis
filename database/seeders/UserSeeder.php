<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // User::truncate();
        // DB::table('role_user')->truncate();
        $adminRole   = Role::where('name', 'Admin')->first();
        $admin       = User::create([
            'name'          => 'Admin',
            'email'         => 'admin@gmail.com',
            'password'      => Hash::make('password')
        ]);
        $admin->roles()->attach($adminRole);
    }
}
