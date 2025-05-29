<?php

namespace App\Tests\Unit\Product\Application\Command\UpdateProduct;

use App\Product\Application\Command\UpdateProduct\UpdateProductCommand;
use App\Product\Application\Command\UpdateProduct\UpdateProductCommandHandler;
use App\Product\Domain\Entity\Product;
use App\Product\Domain\Exception\ProductNotFoundException;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\Product\Domain\ValueObject\ProductId;
use App\Product\Domain\ValueObject\ProductName;
use App\Product\Domain\ValueObject\ProductPrice;
use PHPUnit\Framework\TestCase;

class UpdateProductCommandHandlerTest extends TestCase
{
    public function testUpdateProductSuccess(): void
    {
        $repo = $this->createMock(ProductRepositoryInterface::class);
        $product = $this->createMock(Product::class);
        $productId = new ProductId();
        $name = new ProductName('Apple Watch Ultra');
        $price = new ProductPrice(350.99);

        $repo->expects($this->once())->method('find')->with($productId)->willReturn($product);
        $product->expects($this->once())->method('setName')->with($name);
        $product->expects($this->once())->method('setPrice')->with($price);
        $repo->expects($this->once())->method('save')->with($product);

        $handler = new UpdateProductCommandHandler($repo);

        $handler(new UpdateProductCommand($productId, $name, $price));
    }

    public function testUpdateProductNotFound(): void
    {
        $repo = $this->createMock(ProductRepositoryInterface::class);
        $productId = new ProductId();
        $name = new ProductName('Apple Watch Ultra');
        $price = new ProductPrice(350.99);

        $repo->expects($this->once())->method('find')->with($productId)->willReturn(null);

        $this->expectException(ProductNotFoundException::class);

        $handler = new UpdateProductCommandHandler($repo);
        $handler(new UpdateProductCommand($productId, $name, $price));
    }
}
