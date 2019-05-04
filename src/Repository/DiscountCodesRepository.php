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
class DiscountCodesRepository extends ServiceEntityRepository
{
    private $query;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DiscountCodes::class);
        $this->query = $this->getEntityManager()->createQueryBuilder();
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

    public function getDiscountCodeID(string $discountCode)
    {
        $this->query
            ->select('db.id')
            ->from(DiscountCodes::class, 'db')
            ->where('db.code = :discountCode')
            ->andWhere('db.deleted is null')
            ->setParameter(':discountCode', $discountCode);

        return $this->query->getQuery()->getResult();
    }

    public function getDiscountCode(string $orderDiscountCode)
    {
        $this->query
            ->select('db.code, db.discount, db.expiry_date')
            ->from(DiscountCodes::class, 'db')
            ->where('db.code = :discountCode')
            ->andWhere('db.deleted is null')
            ->setParameter(':discountCode', $orderDiscountCode);

        return $this->query->getQuery()->getOneOrNullResult();
    }

    public function increaseDiscountCodeUsedField(object $discountCode)
    {
        $discountCode->setCodeUsed($discountCode->getCodeUsed() + 1);

        try {
            $this->getEntityManager()->persist($discountCode);
            $this->getEntityManager()->flush();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
