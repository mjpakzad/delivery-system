<?php

namespace App\Services;

use App\Models\Business;
use App\Repositories\Contracts\BusinessRepositoryInterface;
use App\Services\Contracts\BusinessServiceInterface;

class BusinessService implements BusinessServiceInterface
{
    public function __construct(private BusinessRepositoryInterface $businessRepository)
    {
    }

    /**
     * @param int $id
     * @return Business
     */
    public function find(int $id): Business
    {
        return $this->businessRepository->find($id);
    }
}
