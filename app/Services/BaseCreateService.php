<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class BaseCreateService implements ServiceInterface
{
    protected $data;

    protected $model;

    public function __construct(Request $request)
    {
        $this->data = $request->all();
        $this->model = $this->getModel();
    }

    abstract public function getModel();

    abstract public function getResourceData();

    public function create(): JsonResource
    {
        $this->model->fill(
            $this->getCreatedData()
        );

        if ($this->model->save()) {
            return $this->getResourceData();
        }

        return new JsonResource([]);
    }

    /**
     * @return array
     */
    public function getCreatedData() : array
    {
        // add or change data array in child class if needed
        return $this->data;
    }

    public function run() : JsonResource
    {
        return $this->create();
    }
}
