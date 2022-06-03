<?php

namespace Database\Seeders;

use App\Models\Role as UserRole;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdminRole = UserRole::ALL['super_admin'];

        foreach (UserRole::ALL as $role_name => $role_id) {
            $role = UserRole::firstOrCreate([
                'id' => $role_id,
                'name' => $role_name,
                'guard_name' => 'api'
            ]);

            if ($role_id == $superAdminRole) {
                $all_permissions = Permission::query()->where('guard_name', 'api')->pluck('id')->toArray();
                $role->permissions()->attach($all_permissions);
            }
        }
    }
}
