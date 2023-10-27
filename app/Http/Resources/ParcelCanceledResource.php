<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParcelCanceledResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'message' => 'The parcel has been canceled successfully.',
            'data' => [
                'From' => $this->origin_name,
                'To' => $this->destination_name,
            ],
        ];
    }
}
