<?php

declare(strict_types=1);

namespace Tests\Models;

use CastModels\Model;

class TaxRule extends Model
{
    public float $rate;
    public string $country;
}
