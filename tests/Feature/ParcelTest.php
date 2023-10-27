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
        $response->assertCreated();
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

    /** @test */
    public function it_shows_pending_parcels()
    {
        $parcel = Parcel::factory()->create();
        $courier = Courier::factory()->create();
        $tokenService = resolve(TokenServiceInterface::class);
        $token = $tokenService->generateCourierToken($courier);
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->getJson(route('v1.parcels.pending'));
        $response->assertJsonStructure([
            'data'  => [
                '*' => [
                    'uuid',
                    'origin_name',
                    'origin_mobile',
                    'origin_address',
                    'origin_latitude',
                    'origin_longitude',
                    'destination_name',
                    'destination_mobile',
                    'destination_address',
                    'destination_latitude',
                    'destination_longitude',
                    'status',
                ]
            ],
        ]);
        $response->assertOk();
    }

    /** @test */
    public function it_shows_couriers_assigned_parcels()
    {
        $courier = Courier::factory()->create();
        $parcel = Parcel::factory()->create(['courier_id' => $courier->id]);
        $tokenService = resolve(TokenServiceInterface::class);
        $token = $tokenService->generateCourierToken($courier);
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->getJson(route('v1.parcels.my'));
        $response->assertJsonStructure([
            'data'  => [
                '*' => [
                    'uuid',
                    'origin_name',
                    'origin_mobile',
                    'origin_address',
                    'origin_latitude',
                    'origin_longitude',
                    'destination_name',
                    'destination_mobile',
                    'destination_address',
                    'destination_latitude',
                    'destination_longitude',
                    'status',
                ]
            ],
        ]);
        $response->assertJsonFragment(['uuid' => $parcel->uuid]);
        $response->assertOk();
        $this->assertDatabaseHas('parcels', ['uuid' => $parcel->uuid, 'courier_id' => $courier->id]);
    }

    /** @test */
    public function id_can_assign_parcel_to_courier(): void
    {
        $courier = Courier::factory()->create();
        $parcel = Parcel::factory()->create(['status' => ParcelStatus::PENDING->value, 'courier_id' => null]);
        $tokenService = resolve(TokenServiceInterface::class);
        $token = $tokenService->generateCourierToken($courier);
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->patchJson(route('v1.parcels.assign', $parcel->uuid));
        $this->assertDatabaseHas('parcels', ['uuid' => $parcel->uuid, 'status' => ParcelStatus::ASSIGNED->value]);
        $response->assertOk();
    }

    /** @test */
    public function id_cant_reassign_parcel(): void
    {
        $courier = Courier::factory()->create();
        $parcel = Parcel::factory()->create(['status' => ParcelStatus::ASSIGNED->value]);
        $tokenService = resolve(TokenServiceInterface::class);
        $token = $tokenService->generateCourierToken($courier);
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->patchJson(route('v1.parcels.assign', $parcel->uuid));
        $response->assertJsonFragment(['message' => 'The parcel is already assigned.']);
        $response->assertForbidden();
    }

    /** @test */
    public function id_can_change_parcel_status_to_at_vendor(): void
    {
        $courier = Courier::factory()->create();
        $parcel = Parcel::factory()->create(['status' => ParcelStatus::ASSIGNED->value, 'courier_id' => $courier->id]);
        $tokenService = resolve(TokenServiceInterface::class);
        $token = $tokenService->generateCourierToken($courier);
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->patchJson(route('v1.parcels.at-vendor', $parcel->uuid));
        $this->assertDatabaseHas('parcels', ['uuid' => $parcel->uuid, 'status' => ParcelStatus::AT_VENDOR->value]);
        $response->assertOk();
    }

    /** @test */
    public function id_can_change_parcel_status_to_picked(): void
    {
        $courier = Courier::factory()->create();
        $parcel = Parcel::factory()->create(['status' => ParcelStatus::AT_VENDOR->value, 'courier_id' => $courier->id]);
        $tokenService = resolve(TokenServiceInterface::class);
        $token = $tokenService->generateCourierToken($courier);
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->patchJson(route('v1.parcels.picked', $parcel->uuid));
        $this->assertDatabaseHas('parcels', ['uuid' => $parcel->uuid, 'status' => ParcelStatus::PICKED->value]);
        $response->assertOk();
    }

    /** @test */
    public function id_can_change_parcel_status_to_delivered(): void
    {
        $courier = Courier::factory()->create();
        $parcel = Parcel::factory()->create(['status' => ParcelStatus::PICKED->value, 'courier_id' => $courier->id]);
        $tokenService = resolve(TokenServiceInterface::class);
        $token = $tokenService->generateCourierToken($courier);
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->patchJson(route('v1.parcels.delivered', $parcel->uuid));
        $this->assertDatabaseHas('parcels', ['uuid' => $parcel->uuid, 'status' => ParcelStatus::DELIVERED->value]);
        $response->assertOk();
    }

    /** @test */
    public function id_cant_change_someone_else_parcel_status_to_at_vendor(): void
    {
        $courier1 = Courier::factory()->create();
        $courier2 = Courier::factory()->create();
        $parcel = Parcel::factory()->create(['status' => ParcelStatus::ASSIGNED->value, 'courier_id' => $courier1->id]);
        $tokenService = resolve(TokenServiceInterface::class);
        $token = $tokenService->generateCourierToken($courier2);
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->patchJson(route('v1.parcels.at-vendor', $parcel->uuid));
        $response->assertJsonFragment(['message' => 'The parcel is not assigned to you.']);
        $response->assertForbidden();
    }

    /** @test */
    public function id_cant_change_someone_else_parcel_status_to_picked(): void
    {
        $courier1 = Courier::factory()->create();
        $courier2 = Courier::factory()->create();
        $parcel = Parcel::factory()->create(['status' => ParcelStatus::AT_VENDOR->value, 'courier_id' => $courier1->id]);
        $tokenService = resolve(TokenServiceInterface::class);
        $token = $tokenService->generateCourierToken($courier2);
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->patchJson(route('v1.parcels.picked', $parcel->uuid));
        $response->assertJsonFragment(['message' => 'The parcel is not assigned to you.']);
        $response->assertForbidden();
    }

    /** @test */
    public function id_cant_change_someone_else_parcel_status_to_delivered(): void
    {
        $courier1 = Courier::factory()->create();
        $courier2 = Courier::factory()->create();
        $parcel = Parcel::factory()->create(['status' => ParcelStatus::PICKED->value, 'courier_id' => $courier1->id]);
        $tokenService = resolve(TokenServiceInterface::class);
        $token = $tokenService->generateCourierToken($courier2);
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->patchJson(route('v1.parcels.delivered', $parcel->uuid));
        $response->assertJsonFragment(['message' => 'The parcel is not assigned to you.']);
        $response->assertForbidden();
    }
}
