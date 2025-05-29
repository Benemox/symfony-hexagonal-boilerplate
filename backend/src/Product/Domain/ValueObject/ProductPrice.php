<?php

namespace App\Product\Domain\ValueObject;

class ProductPrice
{
    private float $value;

    public function __construct(float $value)
    {
        if ($value <= 0) {
            throw new \InvalidArgumentException('Price must be greater than 0.');
        }
        $this->value = $value;
    }

    public function value(): float
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value();
    }
}
