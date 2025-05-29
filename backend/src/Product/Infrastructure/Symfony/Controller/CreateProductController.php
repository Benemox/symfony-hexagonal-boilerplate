<?php

namespace App\Product\Infrastructure\Symfony\Controller;

use App\Product\Application\Command\CreateProduct\CreateProductCommand;
use App\Product\Domain\ValueObject\ProductName;
use App\Product\Domain\ValueObject\ProductPrice;
use App\Product\Infrastructure\Symfony\Model\Request\CreateProductRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;


class CreateProductController extends AbstractController
{
    #[Route('/api/products', name: 'create_product', methods: ['POST'])]
    public function __invoke(
        #[MapRequestPayload] CreateProductRequest $request,
        MessageBusInterface                       $commandBus
    ): JsonResponse
    {
        $command = new CreateProductCommand(
            new ProductName($request->name),
            new ProductPrice($request->price)
        );
        $commandBus->dispatch($command);

        return $this->json(['message' => 'Product created successfully'], 201);
    }
}
