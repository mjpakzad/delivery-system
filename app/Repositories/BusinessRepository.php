<?php

namespace App\Repositories;

use App\Models\Business;
use App\Repositories\Contracts\BusinessRepositoryInterface;

class BusinessRepository extends BaseRepository implements BusinessRepositoryInterface
{
    /**
     * @return string
     */
    public function getModelName(): string
    {
        return Business::class;
    }
}
