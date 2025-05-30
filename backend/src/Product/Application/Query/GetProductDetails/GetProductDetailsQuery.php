<?php

namespace App\Product\Application\Query\GetProductDetails;

use App\Product\Domain\ValueObject\ProductId;
use App\Shared\Domain\Bus\QueryMessageInterface;

class GetProductDetailsQuery implements QueryMessageInterface
{
    public function __construct(public ProductId $id) {}
}
