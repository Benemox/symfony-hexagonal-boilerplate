<?php

namespace App\Product\Application\Query\ListProducts;

class ListProductsQuery
{
    public function __construct(
        public readonly ?ListProductsQueryFilters $filters = null
    ) {}
}
