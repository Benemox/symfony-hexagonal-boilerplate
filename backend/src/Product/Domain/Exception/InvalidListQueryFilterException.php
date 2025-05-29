<?php

namespace App\Product\Domain\Exception;

class InvalidListQueryFilterException extends \InvalidArgumentException
{
    public static function invalidOrderBy(string $value): self
    {
        return new self("Invalid orderBy: '$value'. Allowed: name, price, createdAt.");
    }

    public static function invalidOrderDir(string $value): self
    {
        return new self("Invalid orderDir: '$value'. Allowed: asc, desc.");
    }

    public static function invalidPrice($value): self
    {
        return new self("Invalid price: '$value'. Must be a positive number.");
    }

    public static function invalidCreatedAt(string $value): self
    {
        return new self("Invalid createdAt: '$value'. Must be in ISO 8601 format.");
    }
}
