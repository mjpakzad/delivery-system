<?php

namespace App\Repositories;

use App\Enums\ParcelStatus;
use App\Models\Parcel;
use App\Repositories\Contracts\ParcelRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

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

    /**
     * @param Parcel $parcel
     * @return Parcel
     */
    public function assignToMe(Parcel $parcel): Parcel
    {
        return $this->update($parcel, ['courier_id' => auth()->id(), 'status' => ParcelStatus::ASSIGNED->value, 'locked' => 'forUpdate']);
    }

    /**
     * @param Parcel $parcel
     * @return bool
     */
    public function isAssigned(Parcel $parcel): bool
    {
        return $parcel->courier_id !== null;
    }

    /**
     * @param array $queries
     * @param array $relations
     * @return LengthAwarePaginator|Collection
     */
    public function pending(array $queries = [], array $relations = []): LengthAwarePaginator|Collection
    {
        return $this->getModel()->query()->pending()->get();
    }

    /**
     * @param array $queries
     * @param array $relations
     * @return LengthAwarePaginator|Collection
     */
    public function assigned(array $queries = [], array $relations = []): LengthAwarePaginator|Collection
    {
        return $this->getModel()->query()->assigned()->get();
    }

    /**
     * @param array $queries
     * @param array $relations
     * @return LengthAwarePaginator|Collection
     */
    public function unassigned(array $queries = [], array $relations = []): LengthAwarePaginator|Collection
    {
        return $this->getModel()->query()->unassigned()->get();
    }

    /**
     * @param Parcel $parcel
     * @return Parcel
     */
    public function arrivedToVendor(Parcel $parcel): Parcel
    {
        return $this->update($parcel, ['status' => ParcelStatus::AT_VENDOR->value]);
    }

    /**
     * @param Parcel $parcel
     * @return Parcel
     */
    public function picked(Parcel $parcel): Parcel
    {
        return $this->update($parcel, ['status' => ParcelStatus::PICKED->value, 'picked_at' => now()]);
    }

    /**
     * @param Parcel $parcel
     * @return Parcel
     */
    public function delivered(Parcel $parcel): Parcel
    {
        return $this->update($parcel, ['status' => ParcelStatus::DELIVERED->value, 'delivered_at' => now()]);
    }

    /**
     * @param Parcel $parcel
     * @return bool
     */
    public function isMine(Parcel $parcel): bool
    {
        return $parcel->courier_id === auth()->id();
    }

    /**
     * @param Parcel $parcel
     * @return bool
     */
    public function isAtVendor(Parcel $parcel): bool
    {
        return $parcel->status === ParcelStatus::AT_VENDOR;
    }

    /**
     * @param Parcel $parcel
     * @return bool
     */
    public function isPicked(Parcel $parcel): bool
    {
        return $parcel->status === ParcelStatus::PICKED;
    }

    /**
     * @param Parcel $parcel
     * @return bool
     */
    public function hasAssignedStatus(Parcel $parcel): bool
    {
        return $parcel->status === ParcelStatus::ASSIGNED;
    }
}
