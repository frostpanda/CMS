<?php

namespace App\Repository;

use App\Entity\Subpages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Subpages|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subpages|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subpages[]    findAll()
 * @method Subpages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubpagesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Subpages::class);
    }

    // /**
    //  * @return Subpages[] Returns an array of Subpages objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Subpages
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
