<?php

namespace App\Product\Application\Query\ListProducts;

use App\Shared\Domain\Bus\QueryMessageInterface;

class ListProductsQuery implements QueryMessageInterface
{
    public function __construct(
        public readonly ?ListProductsQueryFilters $filters = null
    ) {}
}
