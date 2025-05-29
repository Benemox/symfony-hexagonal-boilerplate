<?php

namespace App\Product\Infrastructure\Symfony\Controller;

use App\Product\Application\Query\ListProducts\ListProductsQuery;
use App\Product\Application\Query\ListProducts\ListProductsQueryFilters;
use App\Product\Domain\Exception\InvalidListQueryFilterException;
use App\Product\Infrastructure\Symfony\Model\Response\ProductListSchema;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ListProductsController extends AbstractController
{
    #[Route('/api/products', name: 'list_products', methods: ['GET'])]
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

        $products = $queryBus->dispatch(new ListProductsQuery($filters));
        $products = $products->getMessage();

        return $this->json(new ProductListSchema($products));
    }
}
