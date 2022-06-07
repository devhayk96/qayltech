<?php

namespace Database\Seeders;

use App\Models\Role as UserRole;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Enums\Permissions\OrganizationPermissions;
use App\Enums\Permissions\DoctorPermissions;
use App\Enums\Permissions\HospitalPermissions;
use App\Enums\Permissions\PatientPermissions;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissionsArr = [
            'country' => [
                OrganizationPermissions::VIEW,
                HospitalPermissions::VIEW,
                DoctorPermissions::VIEW,
                PatientPermissions::VIEW,
            ],
            'organization' => [
                HospitalPermissions::VIEW,
                DoctorPermissions::VIEW,
                PatientPermissions::VIEW,
            ],
            'hospital' => [
                DoctorPermissions::VIEW,
                DoctorPermissions::CREATE,
                PatientPermissions::VIEW,
                PatientPermissions::CREATE,
                PatientPermissions::ASSIGN,
                PatientPermissions::DELETE,
            ],
            'doctor' => [
                PatientPermissions::VIEW,
                PatientPermissions::ASSIGN,
            ],
            'patient' => [
                PatientPermissions::VIEW_PERSONAL,
            ],
            'hospital_patient' => [
                PatientPermissions::VIEW_PERSONAL,
            ],
        ];

        foreach (UserRole::ALL as $roleName => $roleId) {
            $role = UserRole::firstOrCreate([
                'id' => $roleId,
                'name' => $roleName,
                'guard_name' => 'api'
            ]);
            $permissions = Permission::query()
                ->where('guard_name', 'api');

            if ($roleName != 'super_admin') {
                $permissions->whereIn('name', $permissionsArr[$roleName]);
            }

            $role->permissions()->attach($permissions->pluck('id')->toArray());
        }
    }
}
