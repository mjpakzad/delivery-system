<?php

namespace App\Services\Contracts;

use App\Models\Business;

interface BusinessServiceInterface
{
    public function find(int $id): Business;
}
