<?php

namespace App\Repositories;

use App\Repositories\Contracts\TokenRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class TokenRepository implements TokenRepositoryInterface
{

    /**
     * @param Model $model
     * @param array $abilities
     * @return string
     */
    public function generate(Model $model, array $abilities): string
    {
        return $model->createToken($model->getTable(), $abilities)->plainTextToken;
    }

    /**
     * @param Model $model
     * @return bool
     */
    public function delete(Model $model): bool
    {
        return $model->tokens()->delete();
    }
}
