<?php

namespace Tests\Unit\Rules;
use App\Rules\CpfCnpj;
use Exception;


test('CpfCnpj validation rule correctly validates valid CPF', function () {
    $rule = new CpfCnpj();

    // CPF válido sem formatação
    expect(fn() => $rule->validate('cpf_cnpj', '52998224725', fn($message) => throw new Exception($message)))
        ->not->toThrow(Exception::class);
});

test('CpfCnpj validation rule fails on invalid CPF', function () {
    $rule = new CpfCnpj();

    // CPF inválido
    expect(fn() => $rule->validate('cpf_cnpj', '12345678900', fn($message) => throw new Exception($message)))
        ->toThrow(Exception::class, 'The field cpf_cnpj is not a valid CPF or CNPJ.');
});

test('CpfCnpj validation rule correctly validates valid CNPJ', function () {
    $rule = new CpfCnpj();

    // CNPJ válido sem formatação
    expect(fn() => $rule->validate('cpf_cnpj', '11444777000161', fn($message) => throw new Exception($message)))
        ->not->toThrow(Exception::class);
});

test('CpfCnpj validation rule fails on invalid CNPJ', function () {
    $rule = new CpfCnpj();

    // CNPJ inválido
    expect(fn() => $rule->validate('cpf_cnpj', '00000000000000', fn($message) => throw new Exception($message)))
        ->toThrow(Exception::class, 'The field cpf_cnpj is not a valid CPF or CNPJ.');
});
