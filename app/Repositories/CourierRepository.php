<?php

namespace App\Repositories;

use App\Models\Courier;
use App\Repositories\Contracts\CourierRepositoryInterface;

class CourierRepository extends BaseRepository implements CourierRepositoryInterface
{
    /**
     * @return string
     */
    public function getModelName(): string
    {
        return Courier::class;
    }
}
