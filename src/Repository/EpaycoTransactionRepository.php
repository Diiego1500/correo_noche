<?php

namespace App\Repository;

use App\Entity\EpaycoTransaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EpaycoTransaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method EpaycoTransaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method EpaycoTransaction[]    findAll()
 * @method EpaycoTransaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EpaycoTransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EpaycoTransaction::class);
    }

    // /**
    //  * @return EpaycoTransaction[] Returns an array of EpaycoTransaction objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EpaycoTransaction
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
