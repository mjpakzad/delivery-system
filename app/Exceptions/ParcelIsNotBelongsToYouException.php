<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ParcelIsNotBelongsToYouException extends Exception
{
    public function render(): JsonResponse
    {
        return response()->json([
            'message' => 'The parcel is not belongs to you.',
        ], Response::HTTP_FORBIDDEN);
    }
}
