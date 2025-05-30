<?php

namespace App\Product\Application\Command\UpdateProduct;

use App\Product\Domain\ValueObject\ProductId;
use App\Product\Domain\ValueObject\ProductName;
use App\Product\Domain\ValueObject\ProductPrice;
use App\Shared\Domain\Bus\CommandMessageInterface;

class UpdateProductCommand implements CommandMessageInterface
{
    public function __construct(
        public ProductId $id,
        public ProductName $name,
        public ProductPrice $price
    ) {
    }
}
