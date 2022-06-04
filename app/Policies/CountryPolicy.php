<?php

namespace App\Policies;

use App\Enums\Permissions\CountryPermissions;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CountryPolicy
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
        return $user->hasPermissionTo(CountryPermissions::VIEW);
    }

    /**
     * Determine whether the user can create countries.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->can(CountryPermissions::CREATE)) {
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
        if ($user->can(CountryPermissions::UPDATE)) {
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
        if ($user->can(CountryPermissions::DELETE)) {
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
