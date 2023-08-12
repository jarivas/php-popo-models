<?php

declare(strict_types=1);

namespace Tests\Models;

use CastModels\Model;

class TaxTables extends Model
{
    public TaxTableSelector $default;
    /** @var \Tests\Models\TaxItem[] */
    public array $alternate;
}
