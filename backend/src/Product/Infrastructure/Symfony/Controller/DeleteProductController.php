<?php

namespace App\Product\Infrastructure\Symfony\Controller;

use App\Product\Application\Command\DeleteProduct\DeleteProductCommand;
use App\Product\Domain\ValueObject\ProductId;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use OpenApi\Attributes as OA;

class DeleteProductController extends AbstractController
{
    #[Route('/api/products/{id}', name: 'delete_product', methods: ['DELETE'])]
    #[OA\Delete(
        path: '/api/products/{id}',
        summary: 'Delete a product by ID',
        tags: ['Product'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'UUID of the product to delete',
                schema: new OA\Schema(type: 'string', format: 'uuid')
            )
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: 'Product deleted successfully'
            ),
            new OA\Response(
                response: 404,
                description: 'Product not found'
            )
        ]
    )]
    public function __invoke(string $id, MessageBusInterface $commandBus): JsonResponse
    {
        try {
            $commandBus->dispatch(new DeleteProductCommand(new ProductId($id)));
        } catch (\DomainException $e) {
            return $this->json(['error' => $e->getMessage()], 404);
        }

        return new JsonResponse(['message' => 'Product deleted successfully'], 204);
    }
}
