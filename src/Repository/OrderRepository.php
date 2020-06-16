<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function FindUserOrder($user_id){
        return $this->getEntityManager()
            ->createQuery('
                SELECT orderr
                FROM App:Order orderr
                WHERE orderr.user =:user_id AND orderr.status =:status
            ')
            ->setParameter('user_id', $user_id)
            ->setParameter('status', Order::STATUS[1])
            ->getSingleResult();
    }

    public function FindUserOrderSales($user_id){
        return $this->getEntityManager()
            ->createQuery('
                SELECT orderr
                FROM App:Order orderr
                WHERE orderr.user =:user_id AND orderr.status !=:status
                ORDER BY orderr.realizationDate DESC
            ')
            ->setParameter('user_id', $user_id)
            ->setParameter('status', Order::STATUS[1])
            ->getResult();
    }


    public function FindRecentOrderSales(){
        return $this->getEntityManager()
            ->createQuery('
                SELECT orderr
                FROM App:Order orderr
                WHERE orderr.status =:status
                ORDER BY orderr.realizationDate DESC
            ')
            ->setParameter('status', Order::STATUS[2])
            ->getResult();
    }

    public function FindRecentDispachesSales(){
        return $this->getEntityManager()
            ->createQuery('
                SELECT orderr
                FROM App:Order orderr
                ORDER BY orderr.dispatch_date DESC
            ')
            ->getResult();
    }
}
