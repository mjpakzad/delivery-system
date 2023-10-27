<?php

namespace Tests\Unit;

use App\Enums\ParcelStatus;
use Tests\TestCase;

class ParcelStatusTest extends TestCase
{
    /** @test */
    public function parcel_status_has_expected_statuses(): void
    {
        $statuses = [
            'PENDING' => 0,
            'CANCEL' => 1,
            'ASSIGNED' => 2,
            'AT_VENDOR' => 3,
            'PICKED' => 4,
            'DELIVERED' => 5,
        ];
        $this->assertEquals($statuses, ParcelStatus::options());
    }
}
