<?php

namespace App\Repositories;

use App\Enums\ParcelStatus;
use App\Models\Parcel;
use App\Repositories\Contracts\ParcelRepositoryInterface;

class ParcelRepository extends BaseRepository implements ParcelRepositoryInterface
{
    /**
     * @return string
     */
    public function getModelName(): string
    {
        return Parcel::class;
    }

    /**
     * @param Parcel $parcel
     * @return Parcel
     */
    public function cancel(Parcel $parcel): Parcel
    {
        $parcel->update(['status' => ParcelStatus::CANCEL->value]);
        return $parcel->refresh();
    }

    /**
     * @param Parcel $parcel
     * @return bool
     */
    public function isCancelable(Parcel $parcel): bool
    {
        return in_array($parcel->status->value, [ParcelStatus::PENDING->value, ParcelStatus::ASSIGNED->value]);
    }

    /**
     * @param Parcel $parcel
     * @return bool
     */
    public function isCancelled(Parcel $parcel): bool
    {
        return $parcel->status->value == ParcelStatus::CANCEL->value;
    }
}
