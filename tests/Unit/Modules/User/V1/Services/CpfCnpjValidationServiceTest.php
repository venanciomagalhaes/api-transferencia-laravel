<?php

use App\Modules\User\V1\Services\CpfCnpjValidationService;

beforeEach(function () {
    $this->validator = new CpfCnpjValidationService();
});

it('valida CPF válido', function () {
    $cpfValido = '52998224725'; // CPF válido
    expect($this->validator->isCpf($cpfValido))->toBeTrue();
});

it('invalida CPF inválido', function () {
    $cpfInvalido = '12345678900'; // CPF inválido
    expect($this->validator->isCpf($cpfInvalido))->toBeFalse();
});

it('invalida CPF com todos os dígitos iguais', function () {
    expect($this->validator->isCpf('11111111111'))->toBeFalse();
});

it('valida CNPJ válido', function () {
    $cnpjValido = '77731187000128'; // CNPJ válido
    expect($this->validator->isCnpj($cnpjValido))->toBeTrue();
});

it('invalida CNPJ inválido', function () {
    $cnpjInvalido = '12345678000100'; // CNPJ com dígitos verificadores errados
    expect($this->validator->isCnpj($cnpjInvalido))->toBeFalse();
});

it('invalida CNPJ com todos os dígitos iguais', function () {
    expect($this->validator->isCnpj('00000000000000'))->toBeFalse();
});

it('remove caracteres não numéricos ao validar CPF e CNPJ', function () {
    expect($this->validator->isCpf('529.982.247-25'))->toBeTrue();
    expect($this->validator->isCnpj('77.731.187/0001-28'))->toBeTrue();
});
