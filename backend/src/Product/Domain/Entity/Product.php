<?php

namespace App\Product\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Product\Domain\ValueObject\ProductId;
use App\Product\Domain\ValueObject\ProductName;
use App\Product\Domain\ValueObject\ProductPrice;

#[ORM\Entity]
#[ORM\Table(name: "products")]
class Product
{
    #[ORM\Id]
    #[ORM\Column(type: "string", unique: true)]
    private string $id;

    #[ORM\Column(type: "string")]
    private string $name;

    #[ORM\Column(type: "float")]
    private float $price;

    #[ORM\Column(type: "datetime_immutable")]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: "datetime_immutable")]
    private \DateTimeImmutable $modifiedAt;

    public function __construct(
        ProductId $id,
        ProductName $name,
        ProductPrice $price,
        ?\DateTimeImmutable $createdAt = null
    ) {
        $this->id = (string)$id;
        $this->name = (string)$name;
        $this->price = $price->value();
        $this->createdAt = $createdAt ?? new \DateTimeImmutable();
        $this->modifiedAt = $this->createdAt;
    }

    public function getId(): ProductId
    {
        return new ProductId($this->id);
    }

    public function getName(): ProductName
    {
        return new ProductName($this->name);
    }

    public function getPrice(): ProductPrice
    {
        return new ProductPrice($this->price);
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getModifiedAt(): \DateTimeImmutable
    {
        return $this->modifiedAt ?? $this->createdAt;
    }

    public function setName(ProductName $name): void
    {
        $this->name = (string)$name;
        $this->touch();
    }

    public function setPrice(ProductPrice $price): void
    {
        $this->price = $price->value();
        $this->touch();
    }

    private function touch(): void
    {
        $this->modifiedAt = new \DateTimeImmutable();
    }
}
