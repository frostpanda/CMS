<?php

namespace App\Repository;

use App\Entity\DiscountCodes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DiscountCodes|null find($id, $lockMode = null, $lockVersion = null)
 * @method DiscountCodes|null findOneBy(array $criteria, array $orderBy = null)
 * @method DiscountCodes[]    findAll()
 * @method DiscountCodes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DiscountCodesRepository extends ServiceEntityRepository {

    private $entityManager;

    public function __construct(RegistryInterface $registry) {
        parent::__construct($registry, DiscountCodes::class);
        $this->entityManager = $this->getEntityManager();
    }

    // /**
    //  * @return DiscountCodes[] Returns an array of DiscountCodes objects
    //  */
    /*
      public function findByExampleField($value)
      {
      return $this->createQueryBuilder('d')
      ->andWhere('d.exampleField = :val')
      ->setParameter('val', $value)
      ->orderBy('d.id', 'ASC')
      ->setMaxResults(10)
      ->getQuery()
      ->getResult()
      ;
      }
     */

    /*
      public function findOneBySomeField($value): ?DiscountCodes
      {
      return $this->createQueryBuilder('d')
      ->andWhere('d.exampleField = :val')
      ->setParameter('val', $value)
      ->getQuery()
      ->getOneOrNullResult()
      ;
      }
     */
    public function getDiscountCodeID(string $discountCode) {
        $query = $this->entityManager->createQueryBuilder();

        $query
                ->select('db.id')
                ->from(DiscountCodes::class, 'db')
                ->where('db.code = :discountCode')
                ->andWhere('db.deleted is null')
                ->setParameter(':discountCode', $discountCode)
        ;

        $queryResult = $query->getQuery()->getResult();

        return $queryResult;
    }

    public function getDiscountCode(string $orderDiscountCode) {
        $query = $this->entityManager->createQueryBuilder();

        $query
                ->select('db.code, db.discount, db.expiry_date')
                ->from(DiscountCodes::class, 'db')
                ->where('db.code = :discountCode')
                ->andWhere('db.deleted is null')
                ->setParameter(':discountCode', $orderDiscountCode)
        ;

        $queryResult = $query->getQuery()->getOneOrNullResult();

        return $queryResult;
    }    
    
    public function increaseDiscountCodeUsedField(object $discountCode) {
        $discountCode->setCodeUsed($discountCode->getCodeUsed() + 1);

        $this->entityManager->persist($discountCode);
        $this->entityManager->flush();
    }

}
