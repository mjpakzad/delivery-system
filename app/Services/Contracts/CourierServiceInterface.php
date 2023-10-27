<?php

namespace App\Services\Contracts;

use App\Models\Courier;

interface CourierServiceInterface
{
    public function find(int $id): Courier;
}
