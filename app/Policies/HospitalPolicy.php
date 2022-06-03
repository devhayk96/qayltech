<?php

namespace App\Policies;

use App\Enums\Permissions\HospitalPermissions;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HospitalPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the hospitals.
     *
     * @param User|null $user
     * @return mixed
     */
    public function view(?User $user)
    {
        return $user->hasPermissionTo(HospitalPermissions::VIEW);
    }

    /**
     * Determine whether the user can create hospitals.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->can(HospitalPermissions::CREATE)) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the hospital.
     *
     * @param User $user
     * @return mixed
     */
    public function update(User $user)
    {
        if ($user->can(HospitalPermissions::UPDATE)) {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the hospital.
     *
     * @param User $user
     * @return mixed
     */
    public function delete(User $user)
    {
        if ($user->can(HospitalPermissions::DELETE)) {
            return true;
        }
    }

    /**
     * Determine whether the user can restore the hospital.
     *
     * @param User $user
     * @return mixed
     */
    public function restore(User $user)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the hospital.
     *
     * @param User $user
     * @return mixed
     */
    public function forceDelete(User $user)
    {
        //
    }
}
