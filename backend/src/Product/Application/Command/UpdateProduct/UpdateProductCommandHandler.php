<?php

namespace App\Product\Application\Command\UpdateProduct;

use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\Product\Domain\Exception\ProductNotFoundException;
use App\Product\Domain\ValueObject\ProductId;

class UpdateProductCommandHandler
{
    public function __construct(private ProductRepositoryInterface $repository)
    {}

    public function __invoke(UpdateProductCommand $command): void
    {
        $product = $this->repository->find($command->id);
        if (!$product) {
            throw new ProductNotFoundException("Product not found with id {$command->id->value()}");
        }
        $product->setName($command->name);
        $product->setPrice($command->price);
        $this->repository->save($product);
    }
}
