<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ParcelIsNotCancelableException extends Exception
{
    public function render(): JsonResponse
    {
        return response()->json([
            'message' => 'The parcel is not cancelable.',
        ], Response::HTTP_FORBIDDEN);
    }
}
