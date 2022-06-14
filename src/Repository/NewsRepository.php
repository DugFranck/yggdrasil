<?php

namespace App\Repository;

use App\Classe\SearchNews;
use App\Entity\News;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;

use Doctrine\Persistence\ManagerRegistry;

/**
 * @method News|null find($id, $lockMode = null, $lockVersion = null)
 * @method News|null findOneBy(array $criteria, array $orderBy = null)
 * @method News[]    findAll()
 * @method News[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, News::class);
    }


    /**
     * @param SearchNews $searchNews
     * @return News[]
     */
    public function findWithSearch(SearchNews $searchNews)
    {
        $query = $this
            ->createQueryBuilder('n')
            ->select('c','n')
            ->join('n.categoryNews','c');

        if(!empty($searchNews->categories)){
            $query = $query
                ->andWhere('c.id IN (:categories)')
                ->setParameter('categories', $searchNews->categories);
        }



        return $query->getQuery()->getResult();
    }

}
