<?php

namespace App\Repositories\Contracts;

use App\Models\Parcel;

interface ParcelRepositoryInterface
{
    public function cancel(Parcel $parcel): Parcel;

    public function isCancelable(Parcel $parcel): bool;

    public function isCancelled(Parcel $parcel): bool;
}
