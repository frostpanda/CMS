<?php

namespace App\Repository;

use App\Entity\Orders;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Orders|null find($id, $lockMode = null, $lockVersion = null)
 * @method Orders|null findOneBy(array $criteria, array $orderBy = null)
 * @method Orders[]    findAll()
 * @method Orders[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrdersRepository extends ServiceEntityRepository
{
    private $query;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Orders::class);
        $this->query = $this->getEntityManager()->createQueryBuilder();
    }

    // /**
    //  * @return Orders[] Returns an array of Orders objects
    //  */
    /*
      public function findByExampleField($value)
      {
      return $this->createQueryBuilder('o')
      ->andWhere('o.exampleField = :val')
      ->setParameter('val', $value)
      ->orderBy('o.id', 'ASC')
      ->setMaxResults(10)
      ->getQuery()
      ->getResult()
      ;
      }
     */

    /*
      public function findOneBySomeField($value): ?Orders
      {
      return $this->createQueryBuilder('o')
      ->andWhere('o.exampleField = :val')
      ->setParameter('val', $value)
      ->getQuery()
      ->getOneOrNullResult()
      ;
      }
     */

    public function checkOrderNumber(int $orderNumber)
    {
        $this->query
            ->select('orders.id')
            ->from(Orders::class, 'orders')
            ->where('orders.order_number = :orderNumber')
            ->setParameter(':orderNumber', $orderNumber, \PDO::PARAM_INT);

        if ($this->query->getQuery()->getResult()) {
            return true;
        } else {
            return false;
        }
    }
}
