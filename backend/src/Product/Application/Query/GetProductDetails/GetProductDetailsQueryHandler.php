<?php

namespace App\Product\Application\Query\GetProductDetails;

use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\Product\Domain\Exception\ProductNotFoundException;
use App\Product\Domain\Entity\Product;

class GetProductDetailsQueryHandler
{
    public function __construct(private ProductRepositoryInterface $repository)
    {}

    public function __invoke(GetProductDetailsQuery $query): Product
    {
        $product = $this->repository->find($query->id);
        if (!$product) {
            throw ProductNotFoundException::fromId($query->id->value());
        }
        return $product;
    }
}
