<?php

namespace App\Policies;

use App\Enums\Permissions\DoctorPermissions;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DoctorPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the doctors.
     *
     * @param User|null $user
     * @return mixed
     */
    public function view(?User $user)
    {
        return $user->hasPermissionTo(DoctorPermissions::VIEW);
    }

    /**
     * Determine whether the user can create doctors.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->can(DoctorPermissions::CREATE)) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the doctor.
     *
     * @param User $user
     * @return mixed
     */
    public function update(User $user)
    {
        if ($user->can(DoctorPermissions::UPDATE)) {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the doctor.
     *
     * @param User $user
     * @return mixed
     */
    public function delete(User $user)
    {
        if ($user->can(DoctorPermissions::DELETE)) {
            return true;
        }
    }

    /**
     * Determine whether the user can restore the doctor.
     *
     * @param User $user
     * @return mixed
     */
    public function restore(User $user)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the doctor.
     *
     * @param User $user
     * @return mixed
     */
    public function forceDelete(User $user)
    {
        //
    }
}
