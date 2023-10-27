<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ParcelCollection extends ResourceCollection
{
    public $collects = ParcelResource::class;
}
