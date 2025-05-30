<?php

namespace App\Product\Infrastructure\Symfony\Model\Request;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

#[OA\Schema(
    description: 'Create Product Request',
    required: ['name', 'price']
)]
class CreateProductRequest
{
    public function __construct(
        #[OA\Property(description: 'Product name', type: 'string', example: 'Apple Watch')]
        #[Assert\NotBlank(message: 'Product name cannot be blank')]
        #[Assert\Length(min: 2, minMessage: 'Product name must be at least 2 characters')]
        public string $name,
        #[OA\Property(description: 'Product price', type: 'number', example: 120.50)]
        #[Assert\NotNull(message: 'Price cannot be null')]
        #[Assert\Type('float')]
        #[Assert\Positive(message: 'Price must be greater than 0')]
        public float $price
    ) {
    }
}
