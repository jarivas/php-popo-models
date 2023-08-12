<?php

declare(strict_types=1);

namespace Tests\Models;

use CastModels\Model;

class ShoppingCart extends Model
{
    /** @var \Tests\Models\ShoppingCartItem[] */
    public array $items;
}
