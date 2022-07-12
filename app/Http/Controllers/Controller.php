<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getModel($modelName = false)
    {
        if (!$modelName) {
            $modelName = $this->modelOrRoleName();
        }
        $class = "App\Models\\".ucfirst($modelName);
        return new $class();
    }
}
