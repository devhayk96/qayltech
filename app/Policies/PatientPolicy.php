<?php

namespace App\Policies;

use App\Enums\Permissions\PatientPermissions;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PatientPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the patients.
     *
     * @param User|null $user
     * @return mixed
     */
    public function view(?User $user)
    {
        return $user->hasPermissionTo(PatientPermissions::VIEW);
    }

    /**
     * Determine whether the user can create patients.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->can(PatientPermissions::CREATE)) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the patient.
     *
     * @param User $user
     * @return mixed
     */
    public function update(User $user)
    {
        if ($user->can(PatientPermissions::UPDATE)) {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the patient.
     *
     * @param User $user
     * @return mixed
     */
    public function delete(User $user)
    {
        if ($user->can(PatientPermissions::DELETE)) {
            return true;
        }
    }

    /**
     * Determine whether the user can restore the patient.
     *
     * @param User $user
     * @return mixed
     */
    public function restore(User $user)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the patient.
     *
     * @param User $user
     * @return mixed
     */
    public function forceDelete(User $user)
    {
        //
    }
}
