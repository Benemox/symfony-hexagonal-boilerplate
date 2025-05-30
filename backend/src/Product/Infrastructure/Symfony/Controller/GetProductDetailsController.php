<?php

namespace App\Product\Infrastructure\Symfony\Controller;

use App\Product\Application\Query\GetProductDetails\GetProductDetailsQuery;
use App\Product\Domain\Exception\ProductNotFoundException;
use App\Product\Domain\ValueObject\ProductId;
use App\Product\Infrastructure\Symfony\Model\Response\ProductDetailsSchema;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use OpenApi\Attributes as OA;

class GetProductDetailsController extends AbstractController
{
    #[Route('/api/products/{id}', name: 'get_product', methods: ['GET'])]
    #[OA\Get(
        path: '/api/products/{id}',
        summary: 'Get product details',
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
                description: 'Product details',
                content: new OA\JsonContent(
                    ref: ProductDetailsSchema::class
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Product not found'
            )
        ]
    )]
    public function __invoke(string $id, MessageBusInterface $queryBus): JsonResponse
    {
        $envelope = $queryBus->dispatch(new GetProductDetailsQuery(new ProductId($id)));
        $product = $envelope->last(HandledStamp::class)?->getResult();

        if (!$product) {
            return $this->json(['error' => 'Product not found'], 404);
        }

        return $this->json(new ProductDetailsSchema($product));
    }
}
