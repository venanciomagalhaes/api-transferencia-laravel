<?php

namespace Tests\Feature\Helpers;

use App\Helpers\PaginationHelper;
use Illuminate\Pagination\LengthAwarePaginator;

test('getPagination returns correct pagination structure', function () {
    $items = collect(range(1, 50));
    $perPage = 10;
    $currentPage = 2;
    $paginator = new LengthAwarePaginator(
        $items->forPage($currentPage, $perPage),
        $items->count(),
        $perPage,
        $currentPage,
        ['path' => 'http://example.com/page']
    );

    $pagination = PaginationHelper::getPagination($paginator);

    expect($pagination)->toHaveKeys([
        'total', 'per_page', 'current_page', 'last_page', 'first_page_url', 'last_page_url',
        'next_page_url', 'prev_page_url', 'path', 'from', 'to'
    ])
        ->and($pagination)->not()->toHaveKey('data');
});
