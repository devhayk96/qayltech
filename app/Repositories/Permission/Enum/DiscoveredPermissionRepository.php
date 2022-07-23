<?php

namespace App\Repositories\Permission\Enum;

use App\Enums\Permissions\CountryPermissions;
use App\Enums\Permissions\DevicePermissions;
use App\Enums\Permissions\DoctorPermissions;
use App\Enums\Permissions\HospitalPermissions;
use App\Enums\Permissions\OrganizationPermissions;
use App\Enums\Permissions\PatientPermissions;
use Illuminate\Support\Collection;
use App\Repositories\Interfaces\RepositoryInterface;

class DiscoveredPermissionRepository implements RepositoryInterface
{
    /**
     * @return Collection
     */
    public function get(): Collection
    {
        return collect(
            [
                'countries'     => CountryPermissions::get(),
                'organizations' => OrganizationPermissions::get(),
                'hospitals'     => HospitalPermissions::get(),
                'doctors'       => DoctorPermissions::get(),
                'patients'      => PatientPermissions::get(),
                'devices'       => DevicePermissions::get(),
            ]
        );
    }

}
