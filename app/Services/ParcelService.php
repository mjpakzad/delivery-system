<?php

namespace App\Services;

use App\DTOs\ParcelDTO;
use App\Enums\ParcelStatus;
use App\Exceptions\ParcelIsAlreadyCanceledException;
use App\Exceptions\ParcelISNotBelongsToYou;
use App\Exceptions\ParcelIsNotCancelableException;
use App\Models\Parcel;
use App\Repositories\Contracts\ParcelRepositoryInterface;
use App\Services\Contracts\ParcelServiceInterface;
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
        throw_unless($parcel->business_id == auth()->id(), ParcelISNotBelongsToYou::class);
        return $this->parcelRepository->cancel($parcel);
    }
}
