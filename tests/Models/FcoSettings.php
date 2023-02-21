<?php

declare(strict_types=1);

namespace Tests\Models;

use CastModels\Model;

class FcoSettings extends Model
{
    public string $redirect_mode;
    public Disabled $coupons;
    public Disabled $cart;
    public Shipping $shipping;
    public string $issuers_display_mode;
    public FcoSettingsCheckout $checkout;
    public bool $group_cards;
}
