<?php

namespace App\Product\Application\Query\ListProducts;



use App\Product\Domain\Exception\InvalidListQueryFilterException;

class ListProductsQueryFilters
{
    public function __construct(
        public ?string $orderBy = null,
        public ?string $orderDir = null,
        public ?string $search = null,
        public ?float $price = null,
        public ?string $createdAt = null
    ) {
        if ($orderBy !== null && !in_array($orderBy, ['name', 'price', 'createdAt'])) {
            throw InvalidListQueryFilterException::invalidOrderBy($orderBy);
        }
        if ($orderDir !== null && !in_array(strtolower($orderDir), ['asc', 'desc'])) {
            throw InvalidListQueryFilterException::invalidOrderDir($orderDir);
        }
        if ($price !== null && (!is_numeric($price) || $price < 0)) {
            throw InvalidListQueryFilterException::invalidPrice($price);
        }
        if ($createdAt !== null && \DateTimeImmutable::createFromFormat(DATE_ATOM, $createdAt) === false) {
            throw InvalidListQueryFilterException::invalidCreatedAt($createdAt);
        }
    }
}
