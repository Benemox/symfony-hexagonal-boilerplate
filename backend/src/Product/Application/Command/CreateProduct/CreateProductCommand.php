<?php

namespace App\Product\Application\Command\CreateProduct;

use App\Product\Domain\ValueObject\ProductName;
use App\Product\Domain\ValueObject\ProductPrice;
use App\Shared\Domain\Bus\CommandMessageInterface;

class CreateProductCommand implements CommandMessageInterface
{
    public function __construct(
        public ProductName $name,
        public ProductPrice $price
    ) {}
}
