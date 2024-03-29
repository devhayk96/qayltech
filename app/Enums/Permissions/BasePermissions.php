<?php

namespace App\Enums\Permissions;

abstract class BasePermissions implements PermissionEnum
{
    /**
     * @param  string  $permission
     * @return string
     */
    public static function getLabel(string $permission): string
    {
        return __('policies.' . $permission);
    }

    /**
     * @return array
     */
    public static function get(): array
    {
        return [
            static::VIEW          => static::getLabel(static::VIEW),
            static::VIEW_PERSONAL => static::getLabel(static::VIEW_PERSONAL),
            static::CREATE        => static::getLabel(static::CREATE),
            static::UPDATE        => static::getLabel(static::UPDATE),
            static::DELETE        => static::getLabel(static::DELETE),
            static::DESTROY       => static::getLabel(static::DESTROY),
            static::RESTORE       => static::getLabel(static::RESTORE),
        ];
    }

}
