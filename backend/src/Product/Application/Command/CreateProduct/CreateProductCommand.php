<?php

namespace App\Product\Application\Command\CreateProduct;

use App\Product\Domain\ValueObject\ProductName;
use App\Product\Domain\ValueObject\ProductPrice;

class CreateProductCommand
{
    public function __construct(
        public ProductName $name,
        public ProductPrice $price
    ) {}
}
