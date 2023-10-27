<?php

namespace Database\Factories;

use App\Enums\ParcelStatus;
use App\Models\Business;
use App\Models\Courier;
use App\Models\Parcel;
use Illuminate\Database\Eloquent\Factories\Factory;
use MatanYadaev\EloquentSpatial\Objects\Point;

/**
 * @extends Factory<Parcel>
 */
class ParcelFactory extends Factory
{
    protected $model = Parcel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement(ParcelStatus::class)->value;
        return [
            'uuid' => fake()->uuid(),
            'courier_id' => Courier::factory(),
            'business_id' => Business::factory()->create(),
            'origin_name' => fake()->name(),
            'origin_mobile' => fake()->numerify('09#######'),
            'origin_address' => fake()->address(),
            'origin_location' => new Point(fake()->latitude(), fake()->longitude()),
            'destination_name' => fake()->name(),
            'destination_mobile' => fake()->numerify('09#######'),
            'destination_address' => fake()->address(),
            'destination_location' => new Point(fake()->latitude(), fake()->longitude()),
            'picked_at' => in_array($status, [ParcelStatus::PENDING, ParcelStatus::CANCEL]) ? null : now()->subMinutes(30),
            'delivered_at' => ParcelStatus::DELIVERED ? now() : null,
            'status' => $status,
        ];
    }
}
