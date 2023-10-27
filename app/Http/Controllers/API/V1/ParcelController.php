<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourierLocationRequest;
use App\Http\Requests\ParcelRequest;
use App\Http\Resources\ParcelCanceledResource;
use App\Http\Resources\ParcelCollection;
use App\Http\Resources\ParcelCreatedResource;
use App\Http\Resources\ParcelResource;
use App\Models\Parcel;
use App\Services\Contracts\ParcelServiceInterface;
use Illuminate\Http\JsonResponse;
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
    public function cancel(Parcel $parcel): ParcelCanceledResource
    {
        $parcel = $this->parcelService->cancelParcel($parcel);
        return new ParcelCanceledResource($parcel);
    }

    /**
     * Display a list of pending parcels.
     *
     * @return ParcelCollection
     */
    public function pending(): ParcelCollection
    {
        $parcels = $this->parcelService->pendingParcels();
        return ParcelCollection::make($parcels);
    }

    /**
     * Display a list of parcels that assigned to the courier.
     *
     * @return ParcelCollection
     */
    public function my(): ParcelCollection
    {
        $parcels = $this->parcelService->myParcels();
        return ParcelCollection::make($parcels);
    }

    /**
     * Assign a Parcel to the courier.
     *
     * @param Parcel $parcel
     * @return ParcelResource
     */
    public function assign(Parcel $parcel): ParcelResource
    {
        $parcel = $this->parcelService->assignParcel($parcel);
        return new ParcelResource($parcel);
    }

    /**
     * Change parcel status to at vendor.
     *
     * @param Parcel $parcel
     * @return ParcelResource
     */
    public function atVendor(Parcel $parcel): ParcelResource
    {
        $parcel = $this->parcelService->atVendorParcel($parcel);
        return new ParcelResource($parcel);
    }

    /**
     * Change parcel status to picked.
     *
     * @param Parcel $parcel
     * @return ParcelResource
     */
    public function picked(Parcel $parcel): ParcelResource
    {
        $parcel = $this->parcelService->pickedParcel($parcel);
        return new ParcelResource($parcel);
    }

    /**
     * Change parcel status to delivered.
     *
     * @param Parcel $parcel
     * @return ParcelResource
     */
    public function delivered(Parcel $parcel): ParcelResource
    {
        $parcel = $this->parcelService->deliveredParcel($parcel);
        return new ParcelResource($parcel);
    }

    /**
     * A webhook to update courier location.
     *
     * @param Parcel $parcel
     * @param CourierLocationRequest $request
     * @return JsonResponse
     */
    public function webhook(Parcel $parcel, CourierLocationRequest $request): JsonResponse
    {
        $this->parcelService->courierLocationUpdate($parcel, $request->validated());
        return response()->json([
            'message' => 'The location was successfully sent to the webhook.',
        ]);
    }
}
