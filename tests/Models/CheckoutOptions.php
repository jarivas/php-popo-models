<?php

declare(strict_types=1);

namespace Tests\Models;

use CastModels\Model;

class CheckoutOptions extends Model
{
    public bool $validate_cart;
    public TaxTables $tax_tables;
    public bool $no_shipping_method;
    public bool $use_shipping_notification;
    public ShippingMethods $shipping_methods;
}
