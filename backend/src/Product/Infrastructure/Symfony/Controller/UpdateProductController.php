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


class UpdateProductController extends AbstractController
{
    #[Route('/api/products/{id}', name: 'update_product', methods: ['PUT'])]
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
