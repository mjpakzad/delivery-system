<?php

namespace App\Services;

use App\DTOs\ParcelDTO;
use App\Enums\ParcelStatus;
use App\Events\CourierIsAtVendor;
use App\Events\CourierLocationUpdated;
use App\Events\CourierPickedParcel;
use App\Events\ParcelAssignedToCourier;
use App\Events\ParcelDelivered;
use App\Exceptions\ParcelIsAlreadyAssignedException;
use App\Exceptions\ParcelIsAlreadyCanceledException;
use App\Exceptions\ParcelIsNotAssignedToYou;
use App\Exceptions\ParcelIsNotBelongsToYouException;
use App\Exceptions\ParcelIsNotCancelableException;
use App\Exceptions\ParcelStatusMustBeAssigned;
use App\Exceptions\ParcelStatusMustBeAtVendor;
use App\Exceptions\ParcelStatusMustBePicked;
use App\Models\Parcel;
use App\Repositories\Contracts\ParcelRepositoryInterface;
use App\Services\Contracts\ParcelServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Throwable;

class ParcelService implements ParcelServiceInterface
{
    public function __construct(private readonly ParcelDTO $parcelDTO, private readonly ParcelRepositoryInterface $parcelRepository)
    {
    }

    /**
     * @param array $data
     * @return Parcel
     */
    public function createParcel(array $data): Parcel
    {
        $this->parcelDTO
            ->setUuid()
            ->setBusinessId(auth()->id())
            ->setOriginName($data['origin_name'])
            ->setOriginMobile($data['origin_mobile'])
            ->setOriginAddress($data['origin_address'])
            ->setOriginLocation($data['origin_latitude'], $data['origin_longitude'])
            ->setDestinationName($data['destination_name'])
            ->setDestinationMobile($data['destination_mobile'])
            ->setDestinationAddress($data['destination_address'])
            ->setDestinationLocation($data['destination_latitude'], $data['destination_longitude'])
            ->setStatus(ParcelStatus::PENDING->value);

        return $this->parcelRepository->create([
            'uuid' => $this->parcelDTO->getUuid(),
            'business_id' => $this->parcelDTO->getBusinessId(),
            'origin_name' => $this->parcelDTO->getOriginName(),
            'origin_mobile' => $this->parcelDTO->getOriginMobile(),
            'origin_address' => $this->parcelDTO->getOriginAddress(),
            'origin_location' => $this->parcelDTO->getOriginLocation(),
            'destination_name' => $this->parcelDTO->getDestinationName(),
            'destination_mobile' => $this->parcelDTO->getDestinationMobile(),
            'destination_address' => $this->parcelDTO->getDestinationAddress(),
            'destination_location' => $this->parcelDTO->getDestinationLocation(),
            'status' => $this->parcelDTO->getStatus(),
        ]);
    }

    /**
     * @throws Throwable
     */
    public function cancelParcel(Parcel $parcel): Parcel
    {
        throw_if($this->parcelRepository->isCancelled($parcel), ParcelIsAlreadyCanceledException::class);
        throw_unless($this->parcelRepository->isCancelable($parcel), ParcelIsNotCancelableException::class);
        throw_unless($parcel->business_id == auth()->id(), ParcelIsNotBelongsToYouException::class);
        return $this->parcelRepository->cancel($parcel);
    }

    /**
     * @return LengthAwarePaginator|Collection
     */
    public function pendingParcels(): LengthAwarePaginator|Collection
    {
        return $this->parcelRepository->pending();
    }

    /**
     * @return LengthAwarePaginator|Collection
     */
    public function myParcels(): LengthAwarePaginator|Collection
    {
        return $this->parcelRepository->list(['courier_id' => auth()->id()]);
    }

    /**
     * @param Parcel $parcel
     * @return Parcel
     * @throws Throwable
     */
    public function assignParcel(Parcel $parcel): Parcel
    {
        throw_if($this->parcelRepository->isAssigned($parcel), ParcelIsAlreadyAssignedException::class);
        $parcel = $this->parcelRepository->assignToMe($parcel);
        event(new ParcelAssignedToCourier($parcel));
        return $parcel;
    }

    /**
     * @param Parcel $parcel
     * @return Parcel
     * @throws Throwable
     */
    public function atVendorParcel(Parcel $parcel): Parcel
    {
        throw_unless($this->parcelRepository->isMine($parcel), ParcelIsNotAssignedToYou::class);
        throw_unless($this->parcelRepository->hasAssignedStatus($parcel), ParcelStatusMustBeAssigned::class);
        $parcel = $this->parcelRepository->arrivedToVendor($parcel);
        event(new CourierIsAtVendor($parcel));
        return $parcel;
    }

    /**
     * @param Parcel $parcel
     * @return Parcel
     * @throws Throwable
     */
    public function pickedParcel(Parcel $parcel): Parcel
    {
        throw_unless($this->parcelRepository->isMine($parcel), ParcelIsNotAssignedToYou::class);
        throw_unless($this->parcelRepository->isAtVendor($parcel), ParcelStatusMustBeAtVendor::class);
        $parcel = $this->parcelRepository->picked($parcel);
        event(new CourierPickedParcel($parcel));
        return $parcel;
    }

    /**
     * @param Parcel $parcel
     * @return Parcel
     * @throws Throwable
     */
    public function deliveredParcel(Parcel $parcel): Parcel
    {
        throw_unless($this->parcelRepository->isMine($parcel), ParcelIsNotAssignedToYou::class);
        throw_unless($this->parcelRepository->isPicked($parcel), ParcelStatusMustBePicked::class);
        $parcel = $this->parcelRepository->delivered($parcel);
        event(new ParcelDelivered($parcel));
        return $parcel;
    }

    /**
     * @param Parcel $parcel
     * @param array $data
     * @return Parcel
     */
    public function courierLocationUpdate(Parcel $parcel, array $data): Parcel
    {
        event(new CourierLocationUpdated($parcel, $data));
        return $parcel;
    }
}
