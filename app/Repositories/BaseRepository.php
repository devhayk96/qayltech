<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

use App\Repositories\Interfaces\RepositoryInterface;
use App\Helpers\Model\SchemaHelper;

abstract class BaseRepository implements RepositoryInterface
{
    const DEFAULT_PAGE_SIZE = 15;

    protected $select = ['*'];
    protected $where  = [];
    protected $query;
    protected $fields = [];

    public function __construct()
    {
        $this->query  = $this->getModel();

        //@todo optimize, because after new repository instance queried same data
//        $this->fields = SchemaHelper::getTableColumns($this->getModel());
    }

    /**
     * @return string[]
     */
    public function getSelect(): array
    {
        return $this->select;
    }

    /**
     * @param  array  $select
     * @return $this
     */
    public function setSelect(array $select): self
    {
        $this->select = $select;

        return $this;
    }

    /**
     * @return array
     */
    public function getWhere(): array
    {
        return $this->where;
    }

    /**
     * @param  array  $where
     * @return $this
     */
    public function setWhere(array $where): self
    {
        $this->where = array_filter(
            $where,
            function ($whereCondition) {
                return in_array($whereCondition[0], SchemaHelper::getTableColumns($this->getModel()));
            }
        );

        return $this;
    }

    /**
     * @return $this
     * @todo оптимизировать производительность, исключить из запроса в БД исключенные поля
     *
     */
    public function all(): self
    {
        $request = \request();

        return $this;
    }

    /**
     * @param  int  $pageSize
     * @return Collection
     */
    public function paginate(int $pageSize = 0)
    {
        if ($pageSize <= 0) {
            $pageSize = static::DEFAULT_PAGE_SIZE;
        }

        return $this->query
            ->select(
                $this->getSelect()
            )
            ->where(
                $this->getWhere()
            )
            ->paginate($pageSize);
    }

    /**
     * @return Collection
     */
    public function get(): Collection
    {
        return $this->getBuilder()->get();
    }

    /**
     * @param  string  $valueField
     * @return array
     */
    public function getAsKeyValue(string $valueField): array
    {
        return $this->get()->mapWithKeys(
            function ($item) use ($valueField) {
                return [$item->id => '#'.$item->id.' '.$item->$valueField];
            }
        )->toArray();
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->get()->count();
    }

    /**
     * @return Builder
     */
    public function getBuilder(): Builder
    {
        return $this->query
            ->select(
                $this->getSelect()
            )
            ->where(
                $this->getWhere()
            );
    }

    /**
     * @param  int  $id
     * @return Model
     */
    public function getById(int $id): Model
    {
        if ($id <= 0) {
            throw new \InvalidArgumentException("Invalid user id");
        }

        return $this->getModel()::findOrFail($id);
    }

    /**
     * @return Model
     */
    abstract public function getModel(): Model;
}
