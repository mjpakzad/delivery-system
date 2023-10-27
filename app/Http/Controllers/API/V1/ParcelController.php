<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ParcelRequest;
use App\Http\Resources\ParcelCanceledResource;
use App\Http\Resources\ParcelCreatedResource;
use App\Models\Parcel;
use App\Services\Contracts\ParcelServiceInterface;
use Illuminate\Http\Request;
use Throwable;

class ParcelController extends Controller
{
    public function __construct(private readonly ParcelServiceInterface $parcelService)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ParcelRequest $request
     * @return ParcelCreatedResource
     */
    public function store(ParcelRequest $request): ParcelCreatedResource
    {
        $parcel = $this->parcelService->createParcel($request->validated());
        return new ParcelCreatedResource($parcel);
    }

    /**
     * Cancel the specified parcel if it didn't assign to anyone.
     *
     * @throws Throwable
     */
    public function cancel(Request $request, Parcel $parcel): ParcelCanceledResource
    {
        $parcel = $this->parcelService->cancelParcel($parcel);
        return new ParcelCanceledResource($parcel);
    }
}
