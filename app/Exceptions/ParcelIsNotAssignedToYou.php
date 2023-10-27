<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ParcelIsNotAssignedToYou extends Exception
{
    public function render(): JsonResponse
    {
        return response()->json([
            'message' => 'The parcel is not assigned to you.',
        ], Response::HTTP_FORBIDDEN);
    }
}
