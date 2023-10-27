<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ParcelStatusMustBeAtVendor extends Exception
{
    public function render(): JsonResponse
    {
        return response()->json([
            'message' => 'The parcel status must be at vendor to change.',
        ], Response::HTTP_FORBIDDEN);
    }
}
