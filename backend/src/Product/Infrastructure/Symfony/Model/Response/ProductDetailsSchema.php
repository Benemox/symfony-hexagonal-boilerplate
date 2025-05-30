<?php

namespace App\Product\Infrastructure\Symfony\Model\Response;

use App\Product\Domain\Entity\Product;

class ProductDetailsSchema
{
    public string $id;
    public string $name;
    public float $price;
    public string $createdAt;
    public string $modifiedAt;

    public function __construct(Product $product)
    {

        $this->id = (string)$product->getId();
        $this->name = (string)$product->getName();
        $this->price = $product->getPrice()->value();
        $this->createdAt = $product->getCreatedAt()->format(DATE_ATOM);
        $this->modifiedAt = $product->getModifiedAt()->format(DATE_ATOM);
    }
}
