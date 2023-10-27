<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ParcelIsAlreadyAssignedException extends Exception
{
    public function render(): JsonResponse
    {
        return response()->json([
            'message' => 'The parcel is already assigned.',
        ], Response::HTTP_FORBIDDEN);
    }
}
