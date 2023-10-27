<?php

namespace App\Repositories\Contracts;

use App\Models\Parcel;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ParcelRepositoryInterface
{
    public function cancel(Parcel $parcel): Parcel;
    public function isCancelable(Parcel $parcel): bool;
    public function isCancelled(Parcel $parcel): bool;
    public function assignToMe(Parcel $parcel): Parcel;
    public function arrivedToVendor(Parcel $parcel): Parcel;
    public function picked(Parcel $parcel): Parcel;
    public function delivered(Parcel $parcel): Parcel;
    public function isAssigned(Parcel $parcel): bool;
    public function pending(array $queries = [], array $relations = []): LengthAwarePaginator|Collection;
    public function assigned(array $queries = [], array $relations = []): LengthAwarePaginator|Collection;
    public function unassigned(array $queries = [], array $relations = []): LengthAwarePaginator|Collection;
    public function hasAssignedStatus(Parcel $parcel): bool;
    public function isMine(Parcel $parcel): bool;
    public function isAtVendor(Parcel $parcel): bool;
    public function isPicked(Parcel $parcel): bool;
}
