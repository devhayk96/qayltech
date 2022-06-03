<?php

namespace App\Policies;

use App\Enums\Permissions\CategoryPermissions;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the categories.
     *
     * @param User|null $user
     * @return mixed
     */
    public function view(?User $user)
    {
        return $user->hasPermissionTo(CategoryPermissions::VIEW);
    }

    /**
     * Determine whether the user can create categories.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->can(CategoryPermissions::CREATE)) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the category.
     *
     * @param User $user
     * @return mixed
     */
    public function update(User $user)
    {
        if ($user->can(CategoryPermissions::UPDATE)) {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the category.
     *
     * @param User $user
     * @return mixed
     */
    public function delete(User $user)
    {
        if ($user->can(CategoryPermissions::DELETE)) {
            return true;
        }
    }

    /**
     * Determine whether the user can restore the category.
     *
     * @param User $user
     * @return mixed
     */
    public function restore(User $user)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the category.
     *
     * @param User $user
     * @return mixed
     */
    public function forceDelete(User $user)
    {
        //
    }
}
