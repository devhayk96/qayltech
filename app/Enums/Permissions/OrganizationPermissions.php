<?php

namespace App\Enums\Permissions;

class OrganizationPermissions extends BasePermissions
{
    // Right to view organizations
    const VIEW = 'organizations view';

    // Right to create organizations
    const CREATE = 'organizations create';

    // Right to change organizations
    const UPDATE = 'organizations update';

    // Right to delete organizations
    const DELETE = 'organizations delete';

}
