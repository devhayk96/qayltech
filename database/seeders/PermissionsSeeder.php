<?php

namespace Database\Seeders;

use App\Enums\StatusesEnum;
use App\Models\User;
use App\Services\Permission\InitService;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\Role as UserRole;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds
     *
     * @throws \Exception
     */
    public function run()
    {
        /* Init permissions service */
        (new InitService())->run();

        $superAdminRole = UserRole::ALL['super_admin'];

        foreach (UserRole::ALL as $role_name => $role_id) {
            $role = Role::firstOrCreate([
                'id' => $role_id,
                'name' => $role_name
            ]);

            if ($role_id == $superAdminRole) {
                $all_permissions = Permission::pluck('id')->toArray();
                $role->permissions()->attach($all_permissions);
            }
        }

        $super_admin = User::create([
            'name' => env('APP_NAME', 'QaylTech'),
            'email' => env('SUPER_ADMIN_EMAIL', 'It@qayl.tech'),
            'password' => env('SUPER_ADMIN_PASS', 'MetaGait2022'),
            'status' => StatusesEnum::STATUSES['active'],
        ]);

        $super_admin->assignRole($superAdminRole);
    }
}
