<?php

namespace App\Product\Domain\ValueObject;

use Ramsey\Uuid\Uuid;

class ProductId
{
    private string $value;

    public function __construct(?string $value = null)
    {
        if ($value === null) {
            $value = Uuid::uuid4()->toString();
        }
        if (!Uuid::isValid($value)) {
            throw new \InvalidArgumentException('Invalid UUID format for ProductId.');
        }
        $this->value = $value;
    }
    public static function generate(): self
    {
        return new self(Uuid::uuid4()->toString());
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
