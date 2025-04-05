<?php

namespace Tests\Unit\Helpers;

use App\Helpers\HateoasBuilderHelper;
use Mockery;

test('HateoasBuilderHelper builds correct links', function () {
    $mockModel = Mockery::mock(\Illuminate\Database\Eloquent\Model::class);
    $mockModel->shouldReceive('getAttribute')->with('uuid')->andReturn('1234-5678-91011');

    $helper = new HateoasBuilderHelper($mockModel, 'v1', 'users');

    $links = $helper->self()->index()->create()->update()->delete()->build();

    expect($links)->toHaveKeys(['self', 'index', 'create', 'update', 'delete'])
        ->and($links['self']['href'])->toBe(url('/api/v1/users/1234-5678-91011'))
        ->and($links['self']['method'])->toBe('GET')
        ->and($links['create']['method'])->toBe('POST')
        ->and($links['update']['method'])->toBe('PUT')
        ->and($links['delete']['method'])->toBe('DELETE');
});

test('HateoasBuilderHelper handles null resource', function () {
    $helper = new HateoasBuilderHelper(null, 'v1', 'users');

    $links = $helper->self()->index()->create()->update()->delete()->build();

    expect($links)->toHaveKeys(['self', 'index', 'create', 'update', 'delete'])
        ->and($links['self']['href'])->toBe(url('/api/v1/users'))
        ->and($links['update']['href'])->toBe(url('/api/v1/users'))
        ->and($links['delete']['href'])->toBe(url('/api/v1/users'));
});

test('HateoasBuilderHelper adds a generic link', function () {
    $helper = new HateoasBuilderHelper(null, 'v1', 'users');

    $links = $helper->addGenericLink('custom', 'custom-path', 'PATCH')->build();

    expect($links)->toHaveKey('custom')
        ->and($links['custom']['href'])->toBe(url('/api/v1/custom-path'))
        ->and($links['custom']['method'])->toBe('PATCH');
});
