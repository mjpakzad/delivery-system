<?php

namespace App\Listeners;

use App\Events\CourierLocationUpdated;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendCourierLocationToBusiness
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     * @throws GuzzleException
     */
    public function handle(CourierLocationUpdated $event): void
    {
        $parcel = $event->parcel;
        $data = $event->data;
        $data['parcel'] = $parcel->uuid;
        $client = new Client();
        $response = $client->post($parcel->business->webhook_url, $data);
        if ($response->getStatusCode() == 200) {
            // Can save something that show updating was successful.
        } else {
            // There was a error
        }
    }
}
