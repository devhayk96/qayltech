<?php

namespace App\Repositories\Permission;

use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use Spatie\Permission\Models\Permission;

class PermissionRepository extends BaseRepository
{
    /**
     * @return Model
     */
    public function getModel(): Model
    {
        return new Permission();
    }

    public function orderByName(): self
    {
        $this->query = $this->query->orderBy('name', 'ASC');

        return $this;
    }

}
