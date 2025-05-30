<?php

namespace App\Product\Infrastructure\Symfony\Model\Response;

use App\Product\Domain\Entity\Product;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductListSchema',
    description: 'List of products',
    properties: [
        new OA\Property(
            property: 'products',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/Product')
        )
    ],
    type: 'object'
)]
class ProductListSchema
{
    /** @var array<int, array<string, mixed>> */
    public array $products;

    /**
     * @param Product[] $products
     */
    public function __construct(array $products)
    {
        $this->products = array_map(
            fn(Product $p) => [
                'id' => (string)$p->getId(),
                'name' => (string)$p->getName(),
                'price' => $p->getPrice()->value(),
                'createdAt' => $p->getCreatedAt()->format(DATE_ATOM),
                'modifiedAt' => $p->getModifiedAt()->format(DATE_ATOM),
            ],
            $products
        );
    }
}
