<?php

namespace App\Helpers;

use Ramsey\Uuid\Uuid;

class UuidHelper
{
    public static function generate(): string
    {
        return Uuid::uuid4()->toString();
    }
}
