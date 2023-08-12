<?php

declare(strict_types=1);

namespace Tests\Models;

use CastModels\Model;

class CustomFieldValidation extends Model
{
    public string $type;
    public string $data;
    /** @var \Tests\Models\CustomFieldValidationError[] */
    public array $error;
}
