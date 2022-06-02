<?php
namespace App\Repositories\Interfaces;

use Illuminate\Support\Collection;

interface RepositoryInterface
{
    public function get(): Collection;
}
