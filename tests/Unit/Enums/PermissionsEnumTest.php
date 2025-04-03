<?php

namespace Tests\Unit\Enums;

use App\Enums\PermissionsEnum;

test('PermissionsEnum contains correct values', function () {
    expect(PermissionsEnum::MAKE_A_TRANSFER->value)->toBe('make-a-transfer')
        ->and(PermissionsEnum::RECEIVE_A_TRANSFER->value)->toBe('receive-a-transfer');
});
