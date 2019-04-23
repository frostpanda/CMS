<?php

namespace App\Repository;

use App\Entity\Categories;
use App\Entity\Products;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Categories|null find($id, $lockMode = null, $lockVersion = null)
 * @method Categories|null findOneBy(array $criteria, array $orderBy = null)
 * @method Categories[]    findAll()
 * @method Categories[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoriesRepository extends ServiceEntityRepository {

    public function __construct(RegistryInterface $registry) {
        parent::__construct($registry, Categories::class);
    }

    // /**
    //  * @return Categories[] Returns an array of Categories objects
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
      public function findOneBySomeField($value): ?Categories
      {
      return $this->createQueryBuilder('c')
      ->andWhere('c.exampleField = :val')
      ->setParameter('val', $value)
      ->getQuery()
      ->getOneOrNullResult()
      ;
      }
     */

    /*
      public function getCategories() {
      return $this->createQueryBuilder('p')
      -> select('*')
      -> from('Categories')
      -> getQuery()
      -> getResult()
      ;
      }
     */
    public function getCategoryTable() {
        $db = $this->getEntityManager()->getConnection();

        $query = '
                        SELECT categories.id, categories.name, categories.url, categories.created, categories.modified, 
                        (SELECT COUNT(products.id) FROM products WHERE products.category_id = categories.id AND products.deleted IS NULL) AS number_of_products
                        FROM categories 
                        LEFT JOIN products 
                        ON (categories.id = products.category_id) 
                        WHERE categories.deleted IS NULL
                        GROUP BY categories.id
                    ';

        $exec = $db->prepare($query);
        $exec->execute();

        $categoryTable = $exec->fetchAll();

        return $categoryTable;
    }

    public function checkIfCategoryUrlExist(string $categoryUrl) {
        $query = $this->getEntityManager()->createQueryBuilder();

        $query
                ->select('db.id')
                ->from(Categories::class, 'db')
                ->where('db.url = :url')
                ->andWhere('db.deleted is null')
                ->setParameter(':url', $categoryUrl, \PDO::PARAM_STR)
        ;

        return $query->getQuery()->getResult();
    }

    public function checkIfCategoryHasProducts(int $categoryID) {
        $query = $this->getEntityManager()->createQueryBuilder();

        $query
                ->select('count(db.id)')
                ->from(Products::class, 'db')
                ->where('db.category = :category')
                ->andWhere('db.deleted is null')
                ->setParameter(':category', $categoryID, \PDO::PARAM_INT)
        ;

        $productsInCategory = $query->getQuery()->getSingleScalarResult();

        if ($productsInCategory > 0) {
            return true;
        } else {
            return false;
        }
    }

}
