<?php

namespace App\Http\Resources;

use App\Models\Parcel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use MatanYadaev\EloquentSpatial\Objects\Point;

/**
 * @property string $uuid
 * @property string $origin_name
 * @property string $origin_mobile
 * @property string $origin_address
 * @property Point $origin_location
 * @property string $destination_name
 * @property string $destination_mobile
 * @property string $destination_address
 * @property Point $destination_location
 * @property Carbon $picked_at
 * @property Carbon $delivered_at
 * @property Carbon $created_at
 * @property Carbon $status
 */
class ParcelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'origin_name' => $this->origin_name,
            'origin_mobile' => $this->origin_mobile,
            'origin_address' => $this->origin_address,
            'origin_latitude' => $this->origin_location->latitude,
            'origin_longitude' => $this->origin_location->longitude,
            'destination_name' => $this->destination_name,
            'destination_mobile' => $this->destination_mobile,
            'destination_address' => $this->destination_address,
            'destination_latitude' => $this->destination_location->latitude,
            'destination_longitude' => $this->destination_location->longitude,
            'picked_at' => $this->whenNotNull($this->picked_at),
            'delivered_at' => $this->whenNotNull($this->delivered_at),
            'status' => $this->status?->name,
        ];
    }
}
