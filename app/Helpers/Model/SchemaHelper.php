<?php

namespace App\Helpers\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class SchemaHelper
{
    /**
     * @param  Model  $model
     * @return array
     */
    public static function getTableColumns(Model $model): array
    {
        return Schema::getColumnListing($model->getTable());
    }
}
