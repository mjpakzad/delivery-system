<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ParcelStatusMustBePicked extends Exception
{
    public function render(): JsonResponse
    {
        return response()->json([
            'message' => 'The parcel status must be picked to change.',
        ], Response::HTTP_FORBIDDEN);
    }
}
