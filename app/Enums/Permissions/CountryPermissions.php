<?php

namespace App\Enums\Permissions;

class CountryPermissions extends BasePermissions
{
    // Right to view countries
    const VIEW = 'countries view';

    // Right to view countries
    const VIEW_PERSONAL = 'countries viewPersonal';

    // Right to create countries
    const CREATE = 'countries create';

    // Right to change countries
    const UPDATE = 'countries update';

    // Right to delete countries
    const DELETE = 'countries delete';

    // Right to destroy countries
    const DESTROY = 'countries destroy';

    // Right to restore countries
    const RESTORE = 'countries restore';

}
