<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = Role::ALL;

        foreach ($roles as $role => $id){
            Role::firstOrCreate([
                'id' => $id ,
                'name' => $role
            ]);
        }
    }
}
