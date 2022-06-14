<?php

namespace App\Repository;

use App\Classe\Search;
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

    // /**
    //  * @return Product[] Returns an array of Product objects
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
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @param Search $search
     * @return Product[]
     */
    public function findWithSearch(Search $search)
    {
        $query = $this
            ->createQueryBuilder('p')
            ->select('c','p')
            ->join('p.category','c');

        if(!empty($search->categories)){
            $query = $query
                ->andWhere('c.id IN (:categories)')
                ->setParameter('categories', $search->categories);
        }

        if(!empty($search->string)){
            $query =$query
                ->andWhere('p.name LIKE :string')
                ->setParameter('string',"%{$search->string}%");

        }

        return $query->getQuery()->getResult();
    }

    public function findByDimensions(array $dimensions)
    {
        $qd = $this
            ->createQueryBuilder('p')
            ->select('d','p')
            //je fait une jointure avec l'entity Dimension, avec pour alias "d"
            ->join('p.dimensions', 'd')
            //puis je filtre sur le'id' des dimensions à l'aide d'un IN
            ->andWhere('d.id In (:dimensions')
            ->setParameter('dimensions',$dimensions->dimensions);
        //enfin,je retourne le resultat
        return $qd->getQuery()->getResult();


    }
}