<?php

declare(strict_types=1);

namespace Tests\Models;

use CastModels\Model;

class ShippingMethods extends Model
{
    public ShippingMethodPickup $pickup;

    /** @var \Tests\Models\ShippingMethod[] */
    public array $flat_rate_shipping;
}
