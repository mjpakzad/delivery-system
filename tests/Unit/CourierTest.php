<?php

namespace Tests\Unit;

use App\Models\Courier;
use App\Models\Parcel;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CourierTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function couriers_table_has_expected_columns()
    {
        $this->assertTrue(Schema::hasColumns('couriers', [
            'id', 'name', 'mobile', 'created_at', 'updated_at',
        ]), 1);
    }

    /** @test */
    public function it_has_many_parcels()
    {
        $courier1 = Courier::factory()->create();
        $courier2 = Courier::factory()->create();
        $parcel1 = Parcel::factory()->create(['courier_id' => $courier1->id]);
        $parcel2 = Parcel::factory()->create(['courier_id' => $courier1->id]);
        $parcel3 = Parcel::factory()->create(['courier_id' => $courier2->id]);

        $courier1Parcels = $courier1->parcels;
        $courier2Parcels = $courier2->parcels;

        $this->assertInstanceOf(Parcel::class, $courier1Parcels->first());
        $this->assertCount(2, $courier1Parcels);
        $this->assertTrue($courier1Parcels->contains($parcel1));
        $this->assertTrue($courier1Parcels->contains($parcel2));
        $this->assertTrue($courier2Parcels->contains($parcel3));

        $this->assertInstanceOf(Parcel::class, $courier2Parcels->first());
        $this->assertCount(1, $courier2Parcels);
        $this->assertTrue($courier2Parcels->contains($parcel3));
    }
}
