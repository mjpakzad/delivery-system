<?php

namespace Tests\Unit;

use App\Models\Business;
use App\Models\Courier;
use App\Models\Parcel;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ParcelTest extends TestCase
{
    /** @test */
    public function parcels_table_has_expected_columns()
    {
        $this->assertTrue(Schema::hasColumns('parcels', [
            'id', 'uuid', 'courier_id', 'business_id', 'origin_name', 'origin_mobile', 'origin_address', 'origin_location',
            'destination_name', 'destination_mobile', 'destination_address', 'destination_location', 'picked_at', 'delivered_at',
            'status', 'created_at', 'updated_at',
        ]), 1);
    }

    /** @test */
    public function it_uses_the_correct_route_key_name()
    {
        $parcel = new Parcel();
        $this->assertEquals('uuid', $parcel->getRouteKeyName());
    }

    /** @test */
    public function it_belongs_to_a_courier()
    {
        $courier1 = Courier::factory()->create();
        $courier2 = Courier::factory()->create();
        $parcel1 = Parcel::factory()->create(['courier_id' => $courier1->id]);
        $parcel2 = Parcel::factory()->create(['courier_id' => $courier2->id]);

        $this->assertInstanceOf(Courier::class, $parcel1->courier);
        $this->assertEquals($courier1->id, $parcel1->courier->id);
        $this->assertNotEquals($courier2->id, $parcel1->courier->id);

        $this->assertInstanceOf(Courier::class, $parcel2->courier);
        $this->assertEquals($courier2->id, $parcel2->courier->id);
        $this->assertNotEquals($courier1->id, $parcel2->courier->id);
    }

    /** @test */
    public function it_belongs_to_a_business()
    {
        $business1 = Business::factory()->create();
        $business2 = Business::factory()->create();
        $parcel1 = Parcel::factory()->create(['business_id' => $business1->id]);
        $parcel2 = Parcel::factory()->create(['business_id' => $business2->id]);

        $this->assertInstanceOf(Business::class, $parcel1->business);
        $this->assertEquals($business1->id, $parcel1->business->id);
        $this->assertNotEquals($business2->id, $parcel1->business->id);

        $this->assertInstanceOf(Business::class, $parcel2->business);
        $this->assertEquals($business2->id, $parcel2->business->id);
        $this->assertNotEquals($business1->id, $parcel2->business->id);
    }
}
