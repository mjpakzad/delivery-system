<?php

namespace Tests\Unit;

use App\Models\Business;
use App\Models\Parcel;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class BusinessTest extends TestCase
{
    /** @test */
    public function business_table_has_expected_columns()
    {
        $this->assertTrue(Schema::hasColumns('businesses', [
            'id', 'name', 'webhook_url', 'created_at', 'updated_at',
        ]), 1);
    }

    /** @test */
    public function it_has_many_parcels()
    {
        $business1 = Business::factory()->create();
        $business2 = Business::factory()->create();
        $parcel1 = Parcel::factory()->create(['business_id' => $business1->id]);
        $parcel2 = Parcel::factory()->create(['business_id' => $business1->id]);
        $parcel3 = Parcel::factory()->create(['business_id' => $business2->id]);

        $business1Parcels = $business1->parcels;
        $business2Parcels = $business2->parcels;

        $this->assertInstanceOf(Parcel::class, $business1Parcels->first());
        $this->assertCount(2, $business1Parcels);
        $this->assertTrue($business1Parcels->contains($parcel1));
        $this->assertTrue($business1Parcels->contains($parcel2));
        $this->assertTrue($business2Parcels->contains($parcel3));

        $this->assertInstanceOf(Parcel::class, $business2Parcels->first());
        $this->assertCount(1, $business2Parcels);
        $this->assertTrue($business2Parcels->contains($parcel3));
    }
}
