<?php

namespace App\Repositories;

use App\Repositories\Contracts\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class BaseRepository implements BaseRepositoryInterface
{
    /**
     * @return string
     */
    public function getModelName(): string
    {
        return 'NoModel';
    }

    /**
     * @return Model
     */
    public function getModel(): Model
    {
        return app($this->getModelName());
    }

    /**
     * @param array $queries
     * @param array $relations
     * @return LengthAwarePaginator|Collection
     */
    public function list(array $queries = [], array $relations = []): LengthAwarePaginator|Collection
    {
        $models = $this->getModel()->query()->with($relations);
        return $this->applyQuery($models, $queries)->get();
    }

    /**
     * @param array $parameters
     * @return Model
     */
    public function create(array $parameters): Model
    {
        return $this->getModel()->query()->create($parameters);
    }

    /**
     * @param int $id
     * @return Model
     */
    public function find(int $id): Model
    {
        return $this->getModel()->query()->find($id);
    }

    /**
     * @param Model $model
     * @param array $parameters
     * @return Model
     */
    public
    function update(Model $model, array $parameters): Model
    {
        if(isset($parameters['lock'])) {
            if($parameters['forUpdate']) {
                $model->lockForUpdate();
            } elseif($parameters['shared']) {
                $model->sharedLocked();
            }
            unset($parameters['lock']);
        }
        $model->update($parameters);
        return $model->refresh();
    }

    /**
     * @param Builder $models
     * @param array $queries
     * @return Builder
     */
    public function applyQuery(Builder $models, array $queries = []): Builder
    {
        foreach ($queries as $column => $value) {
            $models->where($column, $value);
        }
        return $models;
    }
}
