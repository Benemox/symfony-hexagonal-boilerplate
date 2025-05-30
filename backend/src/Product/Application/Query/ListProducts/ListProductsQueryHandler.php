<?php

namespace App\Product\Application\Query\ListProducts;

use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\Shared\Domain\Bus\HandlerInterface;

class ListProductsQueryHandler  implements HandlerInterface
{
    public function __construct(private ProductRepositoryInterface $repository)
    {}

    public function __invoke(ListProductsQuery $query): array
    {
        return $this->repository->findAllWithFilters($query->filters);
    }
}
