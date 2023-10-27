<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;

interface TokenRepositoryInterface
{
    public function generate(Model $model, array $abilities): string;

    public function delete(Model $model): bool;
}
