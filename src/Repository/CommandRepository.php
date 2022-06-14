<?php

namespace App\Repository;

use App\Entity\Command;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Command|null find($id, $lockMode = null, $lockVersion = null)
 * @method Command|null findOneBy(array $criteria, array $orderBy = null)
 * @method Command[]    findAll()
 * @method Command[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Command::class);
    }
/*
 *
 * permet d'ajouter une command
 */
    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Command $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
    /*
     *
     * permet supprimer une command
     */
    /**
     * @throws ORMException
     * @throws OptimisticLockException
     *
     */
    public function remove(Command $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
    /*
     * findSuccessCommands
     * Permet d'afficher les commandes dans l'espace mambre de l'utilisateur
     */
    public function findSuccessCommands($user)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.state > 0')
            ->andWhere('c.user = :user')
            ->setParameter('user',$user)
            ->orderBy('c.id','DESC')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Command[] Returns an array of Command objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Command
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
