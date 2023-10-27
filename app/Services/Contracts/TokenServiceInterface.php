<?php

namespace App\Services\Contracts;

use App\Models\Business;
use App\Models\Courier;

interface TokenServiceInterface
{
    public function generateBusinessToken(Business $business): string;

    public function generateCourierToken(Courier $courier): string;
    public function deleteBusinessToken(Business $business): bool;
    public function deleteCourierToken(Courier $courier): bool;
}
