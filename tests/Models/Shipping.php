<?php

declare(strict_types=1);

namespace Tests\Models;

use CastModels\Model;

class Shipping extends Model
{
    public Required $address;
}
