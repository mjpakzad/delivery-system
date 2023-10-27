<?php

namespace App\Services;

use App\Models\Business;
use App\Models\Courier;
use App\Repositories\Contracts\TokenRepositoryInterface;
use App\Services\Contracts\TokenServiceInterface;

class TokenService implements TokenServiceInterface
{
    public function __construct(public TokenRepositoryInterface $tokenRepository)
    {
    }

    /**
     * @param Business $business
     * @return string
     */
    public function generateBusinessToken(Business $business): string
    {
        return $this->tokenRepository->generate($business, ['parcels-create', 'parcels-cancel']);
    }

    /**
     * @param Courier $courier
     * @return string
     */
    public function generateCourierToken(Courier $courier): string
    {
        return $this->tokenRepository->generate($courier, ['parcels-pending', 'parcels-my', 'parcels-assign']);
    }

    /**
     * @param Business $business
     * @return bool
     */
    public function deleteBusinessToken(Business $business): bool
    {
        return $this->tokenRepository->delete($business);
    }

    /**
     * @param Courier $courier
     * @return bool
     */
    public function deleteCourierToken(Courier $courier): bool
    {
        return $this->tokenRepository->delete($courier);
    }
}
