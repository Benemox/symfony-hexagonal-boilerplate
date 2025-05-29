<?php

namespace App\Product\Infrastructure\Repository\Doctrine\Type;

use App\Product\Domain\ValueObject\ProductPrice;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class ProductPriceType extends Type
{
    public const NAME = 'product_price';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getFloatTypeDeclarationSQL($fieldDeclaration);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value !== null ? new ProductPrice((float)$value) : null;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof ProductPrice) {
            return $value->value();
        }
        return $value;
    }

    public function getName()
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}
