<?php

declare(strict_types=1);

namespace Tests\Models;

use CastModels\Model;

class DescriptionRight extends Model
{
    /** @var \Tests\Models\DescriptionRightItem[] */
    public array $value;
}
