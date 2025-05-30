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
use OpenApi\Attributes as OA;

class CreateProductController extends AbstractController
{
    #[Route('/api/products', name: 'create_product', methods: ['POST'])]
    #[OA\Post(
        path: '/api/products',
        summary: 'Create product',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: CreateProductRequest::class,
                example: [
                    'name' => 'Apple Watch',
                    'price' => 120.50
                ]
            )
        ),
        tags: ['Product'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Product created'
            ),
            new OA\Response(
                response: 400,
                description: 'Bad request'
            )
        ]
    )]
    public function __invoke(
        #[MapRequestPayload] CreateProductRequest $request,
        MessageBusInterface $commandBus
    ): JsonResponse
    {
        $command = new CreateProductCommand(
            new ProductName($request->name),
            new ProductPrice($request->price)
        );
       $product = $commandBus->dispatch($command);

        return $this->json(['message' => 'Product created successfully'], 201);
    }
}
