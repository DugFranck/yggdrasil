<?php

namespace App\Repository;

use App\Entity\PriceSending;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PriceSending|null find($id, $lockMode = null, $lockVersion = null)
 * @method PriceSending|null findOneBy(array $criteria, array $orderBy = null)
 * @method PriceSending[]    findAll()
 * @method PriceSending[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PriceSendingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PriceSending::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(PriceSending $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(PriceSending $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
    //  * @return PriceSending[] Returns an array of PriceSending objects
    //  */

    public function findByZoneAndPoidsAndCarrier($totalPoids,$zone,$carrier)
    {

        return $this->createQueryBuilder('p')
                ->where("p.poids > ?1")
                ->orWhere("p.poids = ?2")
                ->andWhere("p.zone = ?3")
                ->andWhere("p.carrier = ?4")
                ->setParameter(1, $totalPoids)
                ->setParameter(2, $totalPoids)
                ->setParameter(3, $zone)
                ->setParameter(4, $carrier)
                ->orderBy('p.id', 'ASC')
                ->setMaxResults(1)
                ->getQuery()
                ->getResult();

    }


    /*
    public function findOneBySomeField($value): ?PriceSending
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
