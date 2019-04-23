<?php

namespace App\Repository;

use App\Entity\Products;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @method Products|null find($id, $lockMode = null, $lockVersion = null)
 * @method Products|null findOneBy(array $criteria, array $orderBy = null)
 * @method Products[]    findAll()
 * @method Products[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductsRepository extends ServiceEntityRepository {
    private $request;
    
    public function __construct(RequestStack $requestStack, RegistryInterface $registry) {
        parent::__construct($registry, Products::class);
        $this->request = $requestStack->getCurrentRequest();
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
      public function findByExampleField($value)
      {
      return $this->createQueryBuilder('p')
      ->andWhere('p.exampleField = :val')
      ->setParameter('val', $value)
      ->orderBy('p.id', 'ASC')
      ->setMaxResults(10)
      ->getQuery()
      ->getResult()
      ;
      }
     */

    /*
      public function findOneBySomeField($value): ?Product
      {
      return $this->createQueryBuilder('p')
      ->andWhere('p.exampleField = :val')
      ->setParameter('val', $value)
      ->getQuery()
      ->getOneOrNullResult()
      ;
      }
     */
    public function getProductList() {
        $query = $this->getEntityManager()->createQueryBuilder();

        $query
                ->select('products.name, products.url')
                ->from(Products::class, 'products')
                ->where('products.deleted is null')
                ->andWhere('products.on_stock = 1')
                ->orderBy('products.name', 'ASC')
        ;

        return $query->getQuery()->getResult();
    }

    public function getProductName($productID) {
        $query = $this->getEntityManager()->createQueryBuilder();

        $query
                ->select('products.name')
                ->from(Products::class, 'products')
                ->where('products.name = :productID')
                ->setParameter(':productID', $productID, \PDO::PARAM_INT)
        ;

        return $query->getQuery()->getOneOrNullResult();
    }

    public function getProductByID($productID) {
        $query = $this->getEntityManager()->createQueryBuilder();

        $query
                ->select('products.id, products.name, products.price, products.old_price')
                ->from(Products::class, 'products')
                ->where('products.id = :productID')
                ->setParameter(':productID', $productID, \PDO::PARAM_INT)
        ;

        return $query->getQuery()->getOneOrNullResult();
    }

    public function getProductByURL($productURL) {
        $query = $this->getEntityManager()->createQueryBuilder();

        $query
                ->select('products.id, products.name, products.price, products.old_price')
                ->from(Products::class, 'products')
                ->where('products.url = :productURL')
                ->setParameter(':productURL', $productURL, \PDO::PARAM_STR)
        ;

        return $query->getQuery()->getOneOrNullResult();
    }

    public function checkIfProductUrlExist($formFieldUrl) {     
        
        if ($this->request->attributes->get('currentProductUrl') === $formFieldUrl['url']) {
            $this->request->attributes->remove('currentProductUrl');
            return array();
        }
        
        $query = $this->getEntityManager()->createQueryBuilder();

        $query
                ->select('products.id')
                ->from(Products::class, 'products')
                ->where('products.url = :formFieldUrl')
                ->andWhere('products.deleted is null')
                ->setParameter(':formFieldUrl', $formFieldUrl['url'], \PDO::PARAM_STR)
        ;

        return $query->getQuery()->getArrayResult();
    }

}
