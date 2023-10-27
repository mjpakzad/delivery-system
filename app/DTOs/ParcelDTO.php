<?php

namespace App\DTOs;

use Illuminate\Support\Str;
use MatanYadaev\EloquentSpatial\Objects\Point;

class ParcelDTO
{
    private string $uuid;
    private int $courierId;
    private int $businessId;
    private string $originName;
    private string $originMobile;
    private string $originAddress;
    private Point $originLocation;
    private string $destinationName;
    private string $destinationMobile;
    private string $destinationAddress;
    private Point $destinationLocation;
    private $pickedAt;
    private $deliveredAt;
    private int $status;

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getCourierId(): int
    {
        return $this->courierId;
    }

    public function getBusinessId(): int
    {
        return $this->businessId;
    }

    public function getOriginName(): string
    {
        return $this->originName;
    }

    public function getOriginMobile(): string
    {
        return $this->originMobile;
    }

    public function getOriginAddress(): string
    {
        return $this->originAddress;
    }

    public function getOriginLocation(): Point
    {
        return $this->originLocation;
    }

    public function getDestinationName(): string
    {
        return $this->destinationName;
    }

    public function getDestinationMobile(): string
    {
        return $this->destinationMobile;
    }

    public function getDestinationAddress(): string
    {
        return $this->destinationAddress;
    }

    public function getDestinationLocation(): Point
    {
        return $this->destinationLocation;
    }

    public function getPickedAt()
    {
        return $this->pickedAt;
    }

    public function getDeliveredAt()
    {
        return $this->deliveredAt;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setUuid(): self
    {
        $this->uuid = (string) Str::uuid();
        return $this;
    }

    public function setCourierId(int $courierId): self
    {
        $this->courierId = $courierId;
        return $this;
    }

    public function setBusinessId(int $businessId): self
    {
        $this->businessId = $businessId;
        return $this;
    }

    public function setOriginName(string $name): self
    {
        $this->originName = $name;
        return $this;
    }

    public function setOriginMobile(string $mobile): self
    {
        $this->originMobile = $mobile;
        return $this;
    }

    public function setOriginAddress(string $address): self
    {
        $this->originAddress = $address;
        return $this;
    }

    public function setOriginLocation(float $latitude, float $longitude): self
    {
        $this->originLocation = new Point($latitude, $longitude);
        return $this;
    }

    public function setDestinationName(string $name): self
    {
        $this->destinationName = $name;
        return $this;
    }

    public function setDestinationMobile(string $mobile): self
    {
        $this->destinationMobile = $mobile;
        return $this;
    }

    public function setDestinationAddress(string $address): self
    {
        $this->destinationAddress = $address;
        return $this;
    }

    public function setDestinationLocation(float $latitude, float $longitude): self
    {
        $this->destinationLocation = new Point($latitude, $longitude);
        return $this;
    }

    public function setPickedAt($pickedAt): self
    {
        $this->pickedAt = $pickedAt;
        return $this;
    }

    public function setDeliveredAt($deliveredAt): self
    {
        $this->deliveredAt = $deliveredAt;
        return $this;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;
        return $this;
    }
}
