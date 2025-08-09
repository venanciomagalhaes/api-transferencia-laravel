<?php

namespace Tests\Unit\Modules\User\V1\Enums;

namespace Tests\Unit\Modules\User\V1\Enums;

use App\Modules\User\V1\Enums\UserTypeNameEnum;

it('has the expected enum values', function () {
    expect(UserTypeNameEnum::COMMON->value)->toBe('common')
        ->and(UserTypeNameEnum::MERCHANT->value)->toBe('merchant');
});

it('can list all enum cases', function () {
    $cases = UserTypeNameEnum::cases();

    expect($cases)->toHaveLength(2)
        ->and($cases[0])->toBe(UserTypeNameEnum::COMMON)
        ->and($cases[1])->toBe(UserTypeNameEnum::MERCHANT);
});
