<?php

namespace App\Product\Domain\ValueObject;

class ProductName
{
    private string $value;

    public function __construct(string $value)
    {
        $value = trim($value);
        if ($value === '') {
            throw new \InvalidArgumentException('Product name cannot be empty');
        }
        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value();
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
