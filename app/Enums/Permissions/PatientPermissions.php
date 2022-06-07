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

    // Right to assign patients
    const ASSIGN = 'patients assign';

    public static function get(): array
    {
        return [
            static::VIEW          => static::getLabel(static::VIEW),
            static::VIEW_PERSONAL => static::getLabel(static::VIEW_PERSONAL),
            static::CREATE        => static::getLabel(static::CREATE),
            static::UPDATE        => static::getLabel(static::UPDATE),
            static::DELETE        => static::getLabel(static::DELETE),
            static::ASSIGN        => static::getLabel(static::ASSIGN),
        ];
    }
}
