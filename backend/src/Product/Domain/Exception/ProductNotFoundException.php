<?php

namespace App\Product\Domain\Exception;

class ProductNotFoundException extends \DomainException
{
    public static function fromId(string $id): self
    {
        return new self("No product found with id: $id");
    }
}
