<?php

namespace App\Product\Application\Command\DeleteProduct;

use App\Product\Domain\ValueObject\ProductId;
use App\Shared\Domain\Bus\CommandMessageInterface;

class DeleteProductCommand implements CommandMessageInterface
{
    public function __construct(
        public ProductId $id
    ) {}
}
