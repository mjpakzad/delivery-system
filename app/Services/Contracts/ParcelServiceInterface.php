<?php

namespace App\Services\Contracts;

use App\Models\Parcel;

interface ParcelServiceInterface
{
    public function createParcel(array $data): Parcel;
}
