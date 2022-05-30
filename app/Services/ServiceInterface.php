<?php

namespace App\Services;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Interface ServiceInterface
 * @package App\Services
 */
interface ServiceInterface
{

    public function run() : JsonResource;
}
