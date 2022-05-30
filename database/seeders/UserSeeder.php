<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'role_id' => Role::ALL['super_admin'],
            'name' => env('APP_NAME', 'QaylTech'),
            'email' => env('SUPER_ADMIN_EMAIL', 'admin@gmail.com'),
            'password' => env('SUPER_ADMIN_PASS', '123456'),
        ]);
    }
}
