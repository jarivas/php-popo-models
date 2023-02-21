<?php

declare(strict_types=1);

namespace Tests\Models;

use CastModels\Model;

class ShippingMethod extends Model
{
    public string $id;
    public string $name;
    public string $price;
    public array $allowed_areas;
    public array $excluded_areas;
}
