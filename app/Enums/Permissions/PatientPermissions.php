<?php

namespace App\Enums\Permissions;

class PatientPermissions extends BasePermissions
{
    // Right to view patients
    const VIEW = 'patients view';

    // Right to view patients
    const VIEW_PERSONAL = 'patients viewPersonal';

    // Right to create patients
    const CREATE = 'patients create';

    // Right to change patients
    const UPDATE = 'patients update';

    // Right to delete patients
    const DELETE = 'patients delete';

}
