<?php

namespace App\Enums;

/**
 * Statuses
 */
class StatusesEnum
{
    const ACTIVE_STATUS = 1;
    const NOT_VERIFIED = 2;
    const DELETED_STATUS = 0;
    const DISABLED_STATUS = -1;

    const STATUSES = [
        'active' => self::ACTIVE_STATUS,
        'deleted' => self::DELETED_STATUS,
        'disabled' => self::DISABLED_STATUS,
        'not_verified' => self::NOT_VERIFIED,
    ];

}
