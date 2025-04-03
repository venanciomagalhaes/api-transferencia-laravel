<?php

namespace App\Helpers;

use Illuminate\Pagination\LengthAwarePaginator;

class PaginationHelper
{
    public static function getPagination(LengthAwarePaginator $paginator): array
    {
        $pagination = $paginator->toArray();
        unset($pagination['data']);

        return $pagination;
    }
}
