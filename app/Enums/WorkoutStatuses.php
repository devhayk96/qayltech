<?php

namespace App\Enums;

/**
 * Statuses
 */
class WorkoutStatuses
{
    const START = 1;
    const IN_PROGRESS = 2;
    const FINISH = 3;

    const ALL = [
        'start' => self::START,
        'inProgress' => self::IN_PROGRESS,
        'finish' => self::FINISH,
    ];

}
