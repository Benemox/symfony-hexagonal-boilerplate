<?php

namespace App\Product\Infrastructure\Symfony\Controller;

use App\Product\Application\Command\UpdateProduct\UpdateProductCommand;
use App\Product\Domain\ValueObject\ProductId;
use App\Product\Domain\ValueObject\ProductName;
use App\Product\Domain\ValueObject\ProductPrice;
use App\Product\Infrastructure\Symfony\Model\Request\UpdateProductRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use OpenApi\Attributes as OA;

class UpdateProductController extends AbstractController
{
    #[Route('/api/products/{id}', name: 'update_product', methods: ['PUT'])]
    #[OA\Put(
        path: '/api/products/{id}',
        summary: 'Update product',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: UpdateProductRequest::class,
                example: [
                    'name' => 'Apple Watch Ultra',
                    'price' => 239.99
                ]
            )
        ),
        tags: ['Product'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Product UUID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string', format: 'uuid')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Product updated',
            ),
            new OA\Response(
                response: 404,
                description: 'Product not found'
            )
        ]
    )]
    public function __invoke(
        string $id,
        #[MapRequestPayload] UpdateProductRequest $request,
        MessageBusInterface $commandBus
    ): JsonResponse {
        try {
            $commandBus->dispatch(
                new UpdateProductCommand(
                    new ProductId($id),
                    new ProductName($request->name),
                    new ProductPrice($request->price)
                )
            );
        } catch (\DomainException $e) {
            return $this->json(['error' => $e->getMessage()], 404);
        }

        return $this->json(['message' => 'Product updated successfully']);
    }
}
