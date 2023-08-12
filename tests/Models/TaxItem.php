<?php

declare(strict_types=1);

namespace Tests\Models;

use CastModels\Model;

class TaxItem extends Model
{
    public string $name;
    public bool $standalone;
    /** @var \Tests\Models\TaxRule[] */
    public array $rules;
}
