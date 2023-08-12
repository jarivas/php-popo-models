<?php

declare(strict_types=1);

namespace Tests\Models;

use CastModels\Model;

class Affiliate extends Model
{
    /** @var \Tests\Models\AffiliateSplitPayment[] */
    public array $split_payments;
}
