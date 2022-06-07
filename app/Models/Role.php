<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasFactory;

    const ALL = [
        'super_admin' => 1,
        'country' => 2,
        'organization' => 3,
        'hospital' => 4,
        'doctor' => 5,
        'patient' => 6,
        'hospital_patient' => 7,
    ];

    public function getPermissionIdsAttribute()
    {
        return $this->permissions()->pluck('id')->toArray();
    }

}
