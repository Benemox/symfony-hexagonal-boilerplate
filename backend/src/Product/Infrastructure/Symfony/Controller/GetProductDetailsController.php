<?php

namespace App\Product\Infrastructure\Symfony\Controller;

use App\Product\Application\Query\GetProductDetails\GetProductDetailsQuery;
use App\Product\Domain\Exception\ProductNotFoundException;
use App\Product\Domain\ValueObject\ProductId;
use App\Product\Infrastructure\Symfony\Model\Response\ProductDetailsSchema;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetProductDetailsController extends AbstractController
{
    #[Route('/api/products/{id}', name: 'get_product', methods: ['GET'])]
    public function __invoke(string $id, MessageBusInterface $queryBus): JsonResponse
    {
        try {
            $product = $queryBus->dispatch(new GetProductDetailsQuery(new ProductId($id)));
            $product = $product->getMessage();
            return $this->json(new ProductDetailsSchema($product));
        } catch (ProductNotFoundException $e) {
            return $this->json(['error' => $e->getMessage()], 404);
        }

    }
}
