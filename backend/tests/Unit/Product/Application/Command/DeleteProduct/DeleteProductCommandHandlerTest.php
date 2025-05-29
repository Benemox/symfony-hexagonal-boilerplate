<?php

namespace App\Tests\Unit\Product\Application\Command\DeleteProduct;

use App\Product\Application\Command\DeleteProduct\DeleteProductCommand;
use App\Product\Application\Command\DeleteProduct\DeleteProductCommandHandler;
use App\Product\Domain\Entity\Product;
use App\Product\Domain\Exception\ProductNotFoundException;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\Product\Domain\ValueObject\ProductId;
use PHPUnit\Framework\TestCase;

class DeleteProductCommandHandlerTest extends TestCase
{
    public function testDeleteProductSuccess(): void
    {
        $repo = $this->createMock(ProductRepositoryInterface::class);
        $product = $this->createMock(Product::class);
        $productId = new ProductId();

        $repo->expects($this->once())->method('find')->with($productId)->willReturn($product);
        $repo->expects($this->once())->method('delete')->with($product);

        $handler = new DeleteProductCommandHandler($repo);

        $handler(new DeleteProductCommand($productId));
    }

    public function testDeleteProductNotFound(): void
    {
        $repo = $this->createMock(ProductRepositoryInterface::class);
        $productId = new ProductId();

        $repo->expects($this->once())->method('find')->with($productId)->willReturn(null);

        $this->expectException(ProductNotFoundException::class);

        $handler = new DeleteProductCommandHandler($repo);
        $handler(new DeleteProductCommand($productId));
    }
}
