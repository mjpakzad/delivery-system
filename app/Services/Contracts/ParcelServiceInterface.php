<?php

namespace App\Services\Contracts;

use App\Models\Parcel;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ParcelServiceInterface
{
    public function createParcel(array $data): Parcel;
    public function cancelParcel(Parcel $parcel): Parcel;
    public function pendingParcels(): LengthAwarePaginator|Collection;
    public function myParcels(): LengthAwarePaginator|Collection;
    public function assignParcel($parcel): Parcel;
}
