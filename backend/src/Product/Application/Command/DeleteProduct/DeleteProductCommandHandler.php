<?php

namespace App\Product\Application\Command\DeleteProduct;

use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\Product\Domain\Exception\ProductNotFoundException;
use App\Product\Domain\ValueObject\ProductId;

class DeleteProductCommandHandler
{
    public function __construct(private ProductRepositoryInterface $repository)
    {}

    public function __invoke(DeleteProductCommand $command): void
    {
        $product = $this->repository->find($command->id);
        if (!$product) {
            throw new ProductNotFoundException("Product not found with id {$command->id->value()}");
        }
        $this->repository->delete($product);
    }
}
