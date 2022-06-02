<?php

namespace App\Services\Permission;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use App\Services\ServiceInterface;
use App\Repositories\Permission\PermissionRepository;
use App\Repositories\Permission\Enum\DiscoveredPermissionRepository;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

/**
 * This service generates and saves a list of accesses (permissions) for roles in the database
 *
 * Class InitService
 * @package App\Services\Permission
 */
class InitService
{
    /**
     * @return bool
     */
    protected function initPermissions(): bool
    {
        try {
            // Reset cached roles and permissions
            app()[PermissionRegistrar::class]->forgetCachedPermissions();

            // get permissions previously saved in the database
            $permissions = (new PermissionRepository)->get();

            // get permissions declared by the system, taking into account the kernel and modules
            $discovered_permissions = (new DiscoveredPermissionRepository())->get();

            // disable inactive permissions
            $this->deleteNoActivePermissions($permissions, $discovered_permissions);

            $discovered_permissions->map(
                function ($permissions, $group_name) {
                    collect($permissions)->map(
                        function ($permissionName, $permission) use ($group_name) {
                            if (!Permission::where('name', $permission)->first()) {
                                Permission::create([
                                    'name'       => $permission,
                                    'guard_name' => 'admin',
                                    'group_name' => $group_name
                                ]);
                            }
                        }
                    );
                }
            );
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return false;
        }

        return true;
    }

    /**
     * @param $permissions
     * @param $discoveredPermissions
     * @return bool
     */
    protected function deleteNoActivePermissions($permissions, $discoveredPermissions): bool
    {
        // permissions saved by the database but not declared by the system (for example - the module is disabled) - to be deleted
        $noActivePermissions = array_diff(
            array_values($permissions->pluck('name')->toArray()),
            array_keys($discoveredPermissions->collapse()->toArray())
        );

        array_map(function ($permission) {
            $permissions = Permission::where('name', $permission)->get();

            $permissions->map(function ($permissionModel) {
                $permissionModel->delete();
            });

        }, $noActivePermissions);

        return true;
    }


    public function run()
    {
        if ($this->initPermissions()) {
            return true;
        }

        return false;
    }
}
