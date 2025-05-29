<?php

namespace App\Product\Infrastructure\Symfony\Controller;

use App\Product\Application\Command\DeleteProduct\DeleteProductCommand;
use App\Product\Domain\ValueObject\ProductId;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DeleteProductController extends AbstractController
{
    #[Route('/api/products/{id}', name: 'delete_product', methods: ['DELETE'])]
    public function __invoke(string $id, MessageBusInterface $commandBus): JsonResponse
    {
        try {
            $commandBus->dispatch(new DeleteProductCommand(new ProductId($id)));
        } catch (\DomainException $e) {
            return $this->json(['error' => $e->getMessage()], 404);
        }

        return $this->json(['message' => 'Product deleted successfully']);
    }
}
