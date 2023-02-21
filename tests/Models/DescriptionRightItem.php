<?php

declare(strict_types=1);

namespace Tests\Models;

use CastModels\Model;
use stdClass;

class DescriptionRightItem extends Model
{
    public string $lang;
    public string $description;

    public function __construct(array|stdClass $data = [])
    {
        $this->lang = array_key_first($data);
        $this->description = $data[$this->lang];
    }

    public function toArray(): array
    {
        return [$this->lang => $this->description];
    }
}
