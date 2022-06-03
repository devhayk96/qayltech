<?php

namespace Database\Seeders;

use App\Enums\StatusesEnum;
use App\Models\Role as UserRole;
use Illuminate\Database\Seeder;
use App\Models\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdminRole = UserRole::find(UserRole::ALL['super_admin']);

        $superAdmin = User::create([
            'name' => env('APP_NAME', 'QaylTech'),
            'email' => env('SUPER_ADMIN_EMAIL', 'It@qayl.tech'),
            'password' => env('SUPER_ADMIN_PASS', 'MetaGait2022'),
            'status' => StatusesEnum::STATUSES['active'],
        ]);

        $superAdmin->assignRole($superAdminRole);
    }
}
