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
        return $this->update($parcel, ['courier_id' => auth()->id(), 'status' => ParcelStatus::ASSIGNED]);
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
}
