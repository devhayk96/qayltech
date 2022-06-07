<?php

namespace Database\Seeders;

use App\Models\Role as UserRole;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Enums\Permissions\CountryPermissions;
use App\Enums\Permissions\OrganizationPermissions;
use App\Enums\Permissions\DoctorPermissions;
use App\Enums\Permissions\HospitalPermissions;
use App\Enums\Permissions\PatientPermissions;
use App\Enums\Permissions\CategoryPermissions;

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
        $countryRole = UserRole::ALL['country'];
        $organizationRole = UserRole::ALL['organization'];
        $hospitalRole = UserRole::ALL['hospital'];
        $doctorRole = UserRole::ALL['doctor'];
        $patientRole = UserRole::ALL['patient'];

        $countryPermissionsArr = [
            1 => OrganizationPermissions::VIEW,
            2 => HospitalPermissions::VIEW,
            3 => DoctorPermissions::VIEW,
            4 => PatientPermissions::VIEW,
        ];

        $organizationPermissionsArr = [
            1 => HospitalPermissions::VIEW,
            2 => PatientPermissions::VIEW,
            3 => DoctorPermissions::VIEW,
        ];

        $hospitalPermissionsArr = [
            1 => DoctorPermissions::CREATE,
            2 => PatientPermissions::CREATE,
            3 => PatientPermissions::VIEW,
            4 => DoctorPermissions::VIEW,
        ];

        $doctorPermissionsArr = [
            1 => PatientPermissions::VIEW,
        ];


        foreach (UserRole::ALL as $role_name => $role_id) {
            $role = UserRole::firstOrCreate([
                'id' => $role_id,
                'name' => $role_name,
                'guard_name' => 'api'
            ]);
            $permissions = [];

            if ($role_id == $superAdminRole) {
                $permissions = Permission::query()->where('guard_name', 'api')->pluck('id')->toArray();
            }
            else if ($role_id == $countryRole) {
                $permissions = Permission::query()->where('guard_name', 'api')
                    ->whereIn('name', $countryPermissionsArr)->pluck('id')->toArray();
            }
            else if ($role_id == $organizationRole) {
                $permissions = Permission::query()->where('guard_name', 'api')
                    ->whereIn('name', $organizationPermissionsArr)->pluck('id')->toArray();
            }
            else if ($role_id == $hospitalRole) {
                $permissions = Permission::query()->where('guard_name', 'api')
                    ->whereIn('name', $hospitalPermissionsArr)->pluck('id')->toArray();
            }
            else if ($role_id == $doctorRole) {
                $permissions = Permission::query()->where('guard_name', 'api')
                    ->whereIn('name', $doctorPermissionsArr)->pluck('id')->toArray();
            }
            $role->permissions()->attach($permissions);
        }
    }
}
