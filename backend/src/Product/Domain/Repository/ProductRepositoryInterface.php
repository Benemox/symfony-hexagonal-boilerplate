<?php

namespace App\Product\Domain\Repository;

use App\Product\Application\Query\ListProducts\ListProductsQueryFilters;
use App\Product\Domain\Entity\Product;
use App\Product\Domain\ValueObject\ProductId;

interface ProductRepositoryInterface
{
    public function find(ProductId $id): ?Product;

    public function findByName(string $name): ?Product;
    public function save(Product $product): void;
    public function delete(Product $product): void;

    public function findAllWithFilters(?ListProductsQueryFilters $filters): array;
}
