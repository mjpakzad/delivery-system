<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ParcelIsAlreadyCanceledException extends Exception
{
    public function render(): JsonResponse
    {
        return response()->json([
            'message' => 'The parcel is already canceled.',
        ], Response::HTTP_FORBIDDEN);
    }
}
