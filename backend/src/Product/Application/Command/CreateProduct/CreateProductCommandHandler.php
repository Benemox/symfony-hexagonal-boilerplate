<?php

namespace App\Product\Application\Command\CreateProduct;

use App\Product\Domain\Entity\Product;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\Product\Domain\ValueObject\ProductId;

class CreateProductCommandHandler
{
    public function __construct(private ProductRepositoryInterface $repository)
    {}

    public function __invoke(CreateProductCommand $command): Product
    {
        $id = ProductId::generate();
        $product = new Product($id ,$command->name, $command->price);
        $this->repository->save($product);

        return $product;
    }
}
