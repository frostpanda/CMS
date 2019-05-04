<?php

namespace App\Repository;

use App\Entity\Flavours;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Flavours|null find($id, $lockMode = null, $lockVersion = null)
 * @method Flavours|null findOneBy(array $criteria, array $orderBy = null)
 * @method Flavours[]    findAll()
 * @method Flavours[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FlavoursRepository extends ServiceEntityRepository
{
    private $query;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Flavours::class);
        $this->query = $this->getEntityManager()->createQueryBuilder();
    }

    // /**
    //  * @return Flavours[] Returns an array of Flavours objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Flavours
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function checkIfFlavourExist(string $flavourName)
    {
        $this->query
            ->select('db.name')
            ->from(Flavours::class, 'db')
            ->where('db.name = :flavour')
            ->setParameter(':flavour', $flavourName, \PDO::PARAM_STR);

        return  $this->query->getQuery()->getResult();
    }
}
