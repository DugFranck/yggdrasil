<?php

namespace App\Repository;

use App\Entity\ProductDimensionStock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductDimensionStock|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductDimensionStock|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductDimensionStock[]    findAll()
 * @method ProductDimensionStock[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductDimensionStockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductDimensionStock::class);
    }

    // /**
    //  * @return ProductDimensionStock[] Returns an array of ProductDimensionStock objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProductDimensionStock
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findByOneProductDimension($product,$dimension)
    {
        return $this->createQueryBuilder('p')
            ->Join('p.product', 'pr')
            ->Join('p.dimension', 'd')

            ->andwhere('pr.name =?1')
            ->andwhere('d.name = ?2')
            ->setParameter(1, $product)
            ->setParameter(2, $dimension)





            ->getQuery()
            ->getResult();




    }
}
