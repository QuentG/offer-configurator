<?php

namespace App\Repository;

use App\Entity\Option;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function removeVariants(Product $product): mixed
    {
        $query = $this->createQueryBuilder('p')
            ->delete()
            ->andWhere('p.parentId = :parentId')
            ->andWhere('p.type = :productType')
            ->setParameters([
                'parentId' => $product->getEntityId(),
                'productType' => Product::CHILD_TYPE
            ])
            ->getQuery();

        return $query->execute();
    }

    public function getChildrens(Product $product, array $options): mixed
    {
        return $this->createQueryBuilder('p')
            ->where('p.parentId = :id')
            ->andWhere('p.type = :type')
            ->andWhere('p.optionSelected IN (:options)')
            ->setParameters([
                'id' => $product->getEntityId(),
                'type' => Product::CHILD_TYPE,
                'options' => $options
            ])
            ->getQuery()
            ->getResult()
        ;
    }
}
