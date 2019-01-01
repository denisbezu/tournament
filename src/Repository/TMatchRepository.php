<?php

namespace App\Repository;

use App\Entity\TMatch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TMatch|null find($id, $lockMode = null, $lockVersion = null)
 * @method TMatch|null findOneBy(array $criteria, array $orderBy = null)
 * @method TMatch[]    findAll()
 * @method TMatch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TMatchRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TMatch::class);
    }

    // /**
    //  * @return TMatch[] Returns an array of TMatch objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TMatch
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
