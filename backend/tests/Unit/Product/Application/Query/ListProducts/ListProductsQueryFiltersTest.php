<?php

namespace App\Tests\Unit\Product\Application\Query\ListProducts;



use App\Product\Application\Query\ListProducts\ListProductsQueryFilters;
use App\Product\Domain\Exception\InvalidListQueryFilterException;
use PHPUnit\Framework\TestCase;

class ListProductsQueryFiltersTest extends TestCase
{
    public function testValidFiltersPass()
    {
        $filters = new ListProductsQueryFilters(
            orderBy: 'name',
            orderDir: 'asc',
            search: 'apple',
            price: 120.50,
            createdAt: (new \DateTimeImmutable())->format(DATE_ATOM)
        );
        $this->assertSame('name', $filters->orderBy);
        $this->assertSame('asc', $filters->orderDir);
        $this->assertSame('apple', $filters->search);
        $this->assertSame(120.50, $filters->price);
        $this->assertNotNull(\DateTimeImmutable::createFromFormat(DATE_ATOM, $filters->createdAt));
    }

    public function testInvalidOrderByThrowsException()
    {
        $this->expectException(InvalidListQueryFilterException::class);
        new ListProductsQueryFilters(orderBy: 'invalid');
    }

    public function testInvalidOrderDirThrowsException()
    {
        $this->expectException(InvalidListQueryFilterException::class);
        new ListProductsQueryFilters(orderDir: 'up');
    }

    public function testInvalidPriceThrowsException()
    {
        $this->expectException(InvalidListQueryFilterException::class);
        new ListProductsQueryFilters(price: -5);
    }

    public function testInvalidCreatedAtThrowsException()
    {
        $this->expectException(InvalidListQueryFilterException::class);
        new ListProductsQueryFilters(createdAt: 'notadate');
    }
}
