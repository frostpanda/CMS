<?php

namespace App\Repository;

use App\Entity\Images;
use App\Entity\Products;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Images|null find($id, $lockMode = null, $lockVersion = null)
 * @method Images|null findOneBy(array $criteria, array $orderBy = null)
 * @method Images[]    findAll()
 * @method Images[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImagesRepository extends ServiceEntityRepository
{
    private $query;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Images::class);
        $this->query = $this->getEntityManager()->createQueryBuilder();
    }

    // /**
    //  * @return Images[] Returns an array of Images objects
    //  */
    /*
      public function findByExampleField($value)
      {
      return $this->createQueryBuilder('i')
      ->andWhere('i.exampleField = :val')
      ->setParameter('val', $value)
      ->orderBy('i.id', 'ASC')
      ->setMaxResults(10)
      ->getQuery()
      ->getResult()
      ;
      }
     */

    /*
      public function findOneBySomeField($value): ?Images
      {
      return $this->createQueryBuilder('i')
      ->andWhere('i.exampleField = :val')
      ->setParameter('val', $value)
      ->getQuery()
      ->getOneOrNullResult()
      ;
      }
     */

    public function getProductImagesByProductID(Products $product)
    {
        $this->query
            ->select('images')
            ->from(Images::class, 'images')
            ->where('images.product = :productID')
            ->andWhere('images.deleted is null')
            ->setParameter(':productID', $product->getId(), \PDO::PARAM_INT);

        return $this->query->getQuery()->getResult();
    }
}
