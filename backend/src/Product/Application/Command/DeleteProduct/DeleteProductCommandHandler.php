<?php

namespace App\Product\Application\Command\DeleteProduct;

use App\Product\Domain\Exception\ProductNotFoundException;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\Shared\Domain\Bus\HandlerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
class DeleteProductCommandHandler implements HandlerInterface
{
    public function __construct(private ProductRepositoryInterface $repository)
    {
    }

    public function __invoke(DeleteProductCommand $command): void
    {
        $product = $this->repository->find($command->id);
        if (!$product) {
            throw new ProductNotFoundException("Product not found with id {$command->id->value()}");
        }
        $this->repository->delete($product);
    }
}
