<?php

namespace App\Tests\Unit\Product\Application\Query\GetProductDetails;

use App\Product\Application\Query\GetProductDetails\GetProductDetailsQuery;
use App\Product\Application\Query\GetProductDetails\GetProductDetailsQueryHandler;
use App\Product\Domain\Entity\Product;
use App\Product\Domain\Exception\ProductNotFoundException;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\Product\Domain\ValueObject\ProductId;
use PHPUnit\Framework\TestCase;

class GetProductDetailsQueryHandlerTest extends TestCase
{
    public function testReturnsProductIfFound()
    {
        $repo = $this->createMock(ProductRepositoryInterface::class);
        $product = $this->createMock(Product::class);
        $id = new ProductId();

        $repo->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($product);

        $handler = new GetProductDetailsQueryHandler($repo);

        $result = $handler(new GetProductDetailsQuery($id));
        $this->assertSame($product, $result);
    }

    public function testThrowsExceptionIfNotFound()
    {
        $repo = $this->createMock(ProductRepositoryInterface::class);
        $id = new ProductId();

        $repo->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(null);

        $this->expectException(ProductNotFoundException::class);

        $handler = new GetProductDetailsQueryHandler($repo);
        $handler(new GetProductDetailsQuery($id));
    }
}
