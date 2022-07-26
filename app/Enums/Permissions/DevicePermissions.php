<?php

namespace App\Enums\Permissions;

class DevicePermissions extends BasePermissions
{
    // Right to view devices
    const VIEW = 'devices view';

    // Right to create devices
    const CREATE = 'devices create';

    // Right to change devices
    const UPDATE = 'devices update';

    // Right to delete devices
    const DELETE = 'devices delete';

    // Right to destroy devices
    const DESTROY = 'devices destroy';

    // Right to restore devices
    const RESTORE = 'devices restore';

}
