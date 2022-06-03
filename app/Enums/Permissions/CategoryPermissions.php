<?php

namespace App\Enums\Permissions;

class CategoryPermissions extends BasePermissions
{
    // Right to view categories
    const VIEW = 'categories view';

    // Right to view categories
    const VIEW_PERSONAL = 'categories viewPersonal';

    // Right to create categories
    const CREATE = 'categories create';

    // Right to change categories
    const UPDATE = 'categories update';

    // Right to delete categories
    const DELETE = 'categories delete';

}
