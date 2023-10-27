<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ParcelStatusMustBeAssigned extends Exception
{
    public function render(): JsonResponse
    {
        return response()->json([
            'message' => 'The parcel status must be assigned to change.',
        ], Response::HTTP_FORBIDDEN);
    }
}
