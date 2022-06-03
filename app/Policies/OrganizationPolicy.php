<?php

namespace App\Policies;

use App\Enums\Permissions\OrganizationPermissions;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrganizationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the countries.
     *
     * @param User|null $user
     * @return mixed
     */
    public function view(?User $user)
    {
        return $user->hasPermissionTo(OrganizationPermissions::VIEW);
    }

    /**
     * Determine whether the user can create countries.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->can(OrganizationPermissions::CREATE)) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the country.
     *
     * @param User $user
     * @return mixed
     */
    public function update(User $user)
    {
        if ($user->can(OrganizationPermissions::UPDATE)) {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the country.
     *
     * @param User $user
     * @return mixed
     */
    public function delete(User $user)
    {
        if ($user->can(OrganizationPermissions::DELETE)) {
            return true;
        }
    }

    /**
     * Determine whether the user can restore the country.
     *
     * @param User $user
     * @return mixed
     */
    public function restore(User $user)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the country.
     *
     * @param User $user
     * @return mixed
     */
    public function forceDelete(User $user)
    {
        //
    }
}
