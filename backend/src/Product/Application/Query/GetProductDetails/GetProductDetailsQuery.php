<?php

namespace App\Product\Application\Query\GetProductDetails;

use App\Product\Domain\ValueObject\ProductId;

class GetProductDetailsQuery
{
    public function __construct(public ProductId $id) {}
}
