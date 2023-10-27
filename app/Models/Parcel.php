<?php

namespace App\Models;

use App\Enums\ParcelStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MatanYadaev\EloquentSpatial\Objects\Point;

class Parcel extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'courier_id',
        'business_id',
        'origin_name',
        'origin_mobile',
        'origin_address',
        'origin_location',
        'destination_name',
        'destination_mobile',
        'destination_address',
        'destination_location',
        'picked_at',
        'delivered_at',
        'status',
    ];

    protected $casts = [
        'origin_location' => Point::class,
        'destination_location' => Point::class,
        'picked_at' => 'timestamp',
        'delivered_at' => 'timestamp',
        'status' => ParcelStatus::class,
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    /**
     * @return BelongsTo
     */
    public function courier(): BelongsTo
    {
        return $this->belongsTo(Courier::class);
    }

    /**
     * @return BelongsTo
     */
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function scopePending($query)
    {
        return $query->whereStatus(ParcelStatus::PENDING);
    }
}
