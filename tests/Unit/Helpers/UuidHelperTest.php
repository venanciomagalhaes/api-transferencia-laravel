<?php

namespace Tests\Unit\Helpers;

use App\Helpers\UuidHelper;
use Ramsey\Uuid\Uuid;

test('UuidHelper generates a valid UUID', function () {
    $uuid = UuidHelper::generate();

    expect(Uuid::isValid($uuid))->toBeTrue()
        ->and($uuid)->toMatch('/^[0-9a-fA-F-]{36}$/');
});
