<?php

namespace App\Enums\Permissions;

class DoctorPermissions extends BasePermissions
{
    // Right to view doctors
    const VIEW = 'doctors view';

    // Right to view doctors
    const VIEW_PERSONAL = 'doctors viewPersonal';

    // Right to create doctors
    const CREATE = 'doctors create';

    // Right to change doctors
    const UPDATE = 'doctors update';

    // Right to delete doctors
    const DELETE = 'doctors delete';

    // Right to destroy doctors
    const DESTROY = 'doctors destroy';

    // Right to restore doctors
    const RESTORE = 'doctors restore';

}
