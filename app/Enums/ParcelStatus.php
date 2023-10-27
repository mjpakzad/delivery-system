<?php

namespace App\Enums;

use ArchTech\Enums\Options;

enum ParcelStatus: int
{
    use Options;

    case PENDING = 0;
    case CANCEL = 1;
    case ASSIGNED = 2;
    case AT_VENDOR = 3;
    case PICKED = 4;
    case DELIVERED = 5;
}
