<?php

namespace Tests\Unit\Exceptions;

use App\Exceptions\BusinessException;

class BusinessExceptionTest extends BusinessException {}

test('BusinessException initializes correctly', function () {
    $exception = new BusinessExceptionTest('Custom error message', 400);

    expect($exception)->toBeInstanceOf(BusinessException::class)
        ->and($exception->getMessage())->toBe('Custom error message')
        ->and($exception->getCode())->toBe(400);
});
