<?php

namespace Tests\Feature;

use App\Enums\ParcelStatus;
use App\Models\Business;
use App\Models\Courier;
use App\Models\Parcel;
use App\Services\Contracts\TokenServiceInterface;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ParcelTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function business_can_create_parcel(): void
    {
        $business = Business::factory()->create();
        $parcel = $this->prepareParcelDate();
        $tokenService = resolve(TokenServiceInterface::class);
        $token = $tokenService->generateBusinessToken($business);
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->postJson(route('v1.parcels.store'), $parcel);
        $response->assertJsonStructure([
            'message',
            'data'  => [
                'uuid'
            ],
        ]);
        $response->status(201);
    }

    /** @test */
    public function courier_can_not_create_parcel()
    {
        $courier = Courier::factory()->create();
        $parcel = $this->prepareParcelDate();
        $tokenService = resolve(TokenServiceInterface::class);
        $token = $tokenService->generateCourierToken($courier);
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->postJson(route('v1.parcels.store'), $parcel);
        $response->assertJsonStructure([
            'message',
        ]);
        $response->assertJsonFragment(['message' => 'Invalid ability provided.']);
        $response->assertForbidden();
    }

    /** @test */
    public function business_cant_cancel_someone_else_parcel(): void
    {
        $business1 = Business::factory()->create();
        $business2 = Business::factory()->create();
        $parcel = Parcel::factory()->create(['business_id' => $business1->id, 'status' => ParcelStatus::PENDING->value]);
        $tokenService = resolve(TokenServiceInterface::class);
        $business1Token = $tokenService->generateBusinessToken($business1);
        $business2Token = $tokenService->generateBusinessToken($business2);
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $business1Token])
            ->patchJson(route('v1.parcels.cancel', $parcel->uuid));
        $this->assertDatabaseHas('parcels', ['uuid' => $parcel->uuid, 'status' => ParcelStatus::CANCEL->value]);
    }

    /** @test */
    public function business_cant_cancel_when_courier_at_vendor_parcel(): void
    {
        $business = Business::factory()->create();
        $parcel = Parcel::factory()->create(['business_id' => $business->id, 'status' => ParcelStatus::AT_VENDOR->value]);
        $tokenService = resolve(TokenServiceInterface::class);
        $token = $tokenService->generateBusinessToken($business);
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->patchJson(route('v1.parcels.cancel', $parcel->uuid));
        $response->assertJsonFragment(['message' => 'The parcel is not cancelable.']);
        $response->assertForbidden();
    }

    /** @test */
    public function business_cant_cancel_picked_parcel(): void
    {
        $business = Business::factory()->create();
        $parcel = Parcel::factory()->create(['business_id' => $business->id, 'status' => ParcelStatus::PICKED->value]);
        $tokenService = resolve(TokenServiceInterface::class);
        $token = $tokenService->generateBusinessToken($business);
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->patchJson(route('v1.parcels.cancel', $parcel->uuid));
        $response->assertJsonFragment(['message' => 'The parcel is not cancelable.']);
        $response->assertForbidden();
    }

    /** @test */
    public function business_cant_cancel_delivered_parcel(): void
    {
        $business = Business::factory()->create();
        $parcel = Parcel::factory()->create(['business_id' => $business->id, 'status' => ParcelStatus::DELIVERED->value]);
        $tokenService = resolve(TokenServiceInterface::class);
        $token = $tokenService->generateBusinessToken($business);
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->patchJson(route('v1.parcels.cancel', $parcel->uuid));
        $response->assertJsonFragment(['message' => 'The parcel is not cancelable.']);
        $response->assertForbidden();
    }

    /** @test */
    public function business_cant_cancel_cancelled_parcel(): void
    {
        $business = Business::factory()->create();
        $parcel = Parcel::factory()->create(['business_id' => $business->id, 'status' => ParcelStatus::CANCEL->value]);
        $tokenService = resolve(TokenServiceInterface::class);
        $token = $tokenService->generateBusinessToken($business);
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->patchJson(route('v1.parcels.cancel', $parcel->uuid));
        $response->assertJsonFragment(['message' => 'The parcel is already canceled.']);
        $response->assertForbidden();
    }

    /** @test */
    public function business_cant_cancel_their_parcel(): void
    {
        $business1 = Business::factory()->create();
        $business2 = Business::factory()->create();
        $parcel = Parcel::factory()->create(['business_id' => $business1->id, 'status' => ParcelStatus::PENDING->value]);
        $tokenService = resolve(TokenServiceInterface::class);
        $business1Token = $tokenService->generateBusinessToken($business1);
        $business2Token = $tokenService->generateBusinessToken($business2);
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $business1Token])
            ->patchJson(route('v1.parcels.cancel', $parcel->uuid));
        $this->assertDatabaseHas('parcels', ['uuid' => $parcel->uuid, 'status' => ParcelStatus::CANCEL->value]);
    }

    private function prepareParcelDate(): array
    {
        return [
            'origin_name' => fake()->name(),
            'origin_mobile' => fake()->numerify('09#########'),
            'origin_address' => fake()->address(),
            'origin_latitude' => fake()->latitude(),
            'origin_longitude' => fake()->longitude(),
            'destination_name' => fake()->name(),
            'destination_mobile' => fake()->numerify('09#########'),
            'destination_address' => fake()->address(),
            'destination_latitude' => fake()->latitude(),
            'destination_longitude' => fake()->longitude(),
        ];
    }
}
