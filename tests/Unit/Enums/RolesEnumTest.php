<?php

namespace Tests\Unit\Enums;

use App\Enums\RolesEnum;

test('RolesEnum contains correct values', function () {
    expect(RolesEnum::CUSTOMER->value)->toBe('customer')
        ->and(RolesEnum::MERCHANT->value)->toBe('merchant');
});
