<?php

namespace App\Product\Infrastructure\Repository\Doctrine;

use App\Product\Application\Query\ListProducts\ListProductsQueryFilters;
use App\Product\Domain\Entity\Product;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\Product\Domain\ValueObject\ProductId;
use Doctrine\ORM\EntityManagerInterface;

class ProductRepository implements ProductRepositoryInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function find(ProductId $id): ?Product
    {
        return $this->em->getRepository(Product::class)->find($id->value());
    }

    public function findByName(string $name): ?Product
    {
        return $this->em->getRepository(Product::class)->findOneBy(['name' => $name]);
    }
    public function save(Product $product): void
    {
        $this->em->persist($product);
        $this->em->flush();
    }

    public function delete(Product $product): void
    {
        $this->em->remove($product);
        $this->em->flush();
    }

    public function findAllWithFilters(?ListProductsQueryFilters $filters): array
    {
        $qb = $this->em->getRepository(Product::class)->createQueryBuilder('p');

        if ($filters) {
            if ($filters->search) {
                $qb->andWhere('LOWER(p.name) LIKE :search')
                    ->setParameter('search', '%' . strtolower($filters->search) . '%');
            }
            if ($filters->price !== null) {
                $qb->andWhere('p.price = :price')
                    ->setParameter('price', $filters->price);
            }
            if ($filters->createdAt) {
                $qb->andWhere('p.createdAt = :createdAt')
                    ->setParameter('createdAt', new \DateTimeImmutable($filters->createdAt));
            }
            if (in_array($filters->orderBy, ['name', 'price', 'createdAt'])) {
                $dir = strtolower($filters->orderDir) === 'desc' ? 'DESC' : 'ASC';
                $qb->orderBy('p.' . $filters->orderBy, $dir);
            }
        }

        return $qb->getQuery()->getResult();
    }
}
