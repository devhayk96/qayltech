<?php

namespace App\Enums\Permissions;

class HospitalPermissions extends BasePermissions
{
    // Right to view hospitals
    const VIEW = 'hospitals view';

    // Right to view hospitals
    const VIEW_PERSONAL = 'hospitals viewPersonal';

    // Right to create hospitals
    const CREATE = 'hospitals create';

    // Right to change hospitals
    const UPDATE = 'hospitals update';

    // Right to delete hospitals
    const DELETE = 'hospitals delete';

    // Right to destroy hospitals
    const DESTROY = 'hospitals destroy';

    // Right to restore hospitals
    const RESTORE = 'hospitals restore';

}
