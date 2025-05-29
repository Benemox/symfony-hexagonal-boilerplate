<?php

namespace App\Tests\Unit\Product\Application\Command\CreateProduct;

use App\Product\Application\Command\CreateProduct\CreateProductCommand;
use App\Product\Application\Command\CreateProduct\CreateProductCommandHandler;
use App\Product\Domain\Entity\Product;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\Product\Domain\ValueObject\ProductId;
use App\Product\Domain\ValueObject\ProductName;
use App\Product\Domain\ValueObject\ProductPrice;
use PHPUnit\Framework\TestCase;

class CreateProductCommandHandlerTest extends TestCase
{
    public function testHandleCreatesAndSavesProduct(): void
    {
        $repo = $this->createMock(ProductRepositoryInterface::class);

        $name  = new ProductName('Apple Watch');
        $price = new ProductPrice(120.50);

        $repo->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Product $product) use ($name, $price) {
                return
                    $product->getName()->equals($name) &&
                    $product->getPrice()->equals($price);
            }));

        $handler = new CreateProductCommandHandler($repo);

        $command = new CreateProductCommand(
            $name,
            $price
        );

        $product = $handler($command);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals($name->value(), $product->getName()->value());
        $this->assertEquals($price->value(), $product->getPrice()->value());
    }
}
