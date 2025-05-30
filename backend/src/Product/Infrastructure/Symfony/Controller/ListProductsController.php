<?php

namespace App\Product\Infrastructure\Symfony\Controller;

use App\Product\Application\Query\ListProducts\ListProductsQuery;
use App\Product\Application\Query\ListProducts\ListProductsQueryFilters;
use App\Product\Domain\Exception\InvalidListQueryFilterException;
use App\Product\Infrastructure\Symfony\Model\Response\ProductListSchema;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use OpenApi\Attributes as OA;

class ListProductsController extends AbstractController
{
    #[Route('/api/products', name: 'list_products', methods: ['GET'])]
    #[OA\Get(
        path: '/api/products',
        summary: 'List products',
        tags: ['Product'],
        parameters: [
            new OA\Parameter(
                name: 'orderBy',
                description: 'Order by field (name, price, createdAt)',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', enum: ['name', 'price', 'createdAt'])
            ),
            new OA\Parameter(
                name: 'direction',
                description: 'Order direction',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', enum: ['asc', 'desc'])
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of products',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: ProductListSchema::class)
                )
            )
        ]
    )]
    public function __invoke(Request $request, MessageBusInterface $queryBus): JsonResponse
    {
        try {
            $filters = new ListProductsQueryFilters(
                orderBy: $request->query->get('orderBy'),
                orderDir: $request->query->get('orderDir'),
                search: $request->query->get('search'),
                price: $request->query->get('price') !== null ? (float) $request->query->get('price') : null,
                createdAt: $request->query->get('createdAt')
            );
        } catch (InvalidListQueryFilterException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }


        $envelope = $queryBus->dispatch(new ListProductsQuery($filters));
        $products = $envelope->last(HandledStamp::class)?->getResult();

        return $this->json(new ProductListSchema($products));

    }
}
