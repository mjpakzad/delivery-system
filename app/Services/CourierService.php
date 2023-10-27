<?php

namespace App\Services;

use App\Models\Courier;
use App\Repositories\Contracts\CourierRepositoryInterface;
use App\Services\Contracts\CourierServiceInterface;

class CourierService implements CourierServiceInterface
{
    public function __construct(private CourierRepositoryInterface $courierRepository)
    {
    }

    /**
     * @param int $id
     * @return Courier
     */
    public function find(int $id): Courier
    {
        return $this->courierRepository->find($id);
    }
}
