<?php

namespace App\Product\Application\Command\CreateProduct;

use App\Product\Domain\Entity\Product;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\Product\Domain\ValueObject\ProductId;
use App\Shared\Domain\Bus\HandlerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
class CreateProductCommandHandler implements HandlerInterface
{
    public function __construct(private ProductRepositoryInterface $repository)
    {}

    public function __invoke(CreateProductCommand $command): void
    {
        if ($this->repository->findByName($command->name)) {
            throw new \InvalidArgumentException('Product with this name already exists.');
        }
        $id = ProductId::generate();
        $product = new Product($id ,$command->name, $command->price);
        $this->repository->save($product);
    }
}
