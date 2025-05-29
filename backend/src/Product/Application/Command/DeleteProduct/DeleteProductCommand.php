<?php

namespace App\Product\Application\Command\DeleteProduct;

use App\Product\Domain\ValueObject\ProductId;

class DeleteProductCommand
{
    public function __construct(
        public ProductId $id
    ) {}
}
