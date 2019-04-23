<?php

namespace App\Repository;

use App\Entity\Administrator;
use App\Entity\LoginHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method LoginHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method LoginHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method LoginHistory[]    findAll()
 * @method LoginHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LoginHistoryRepository extends ServiceEntityRepository {

    public function __construct(RegistryInterface $registry) {
        parent::__construct($registry, LoginHistory::class);
    }

    // /**
    //  * @return LoginHistory[] Returns an array of LoginHistory objects
    //  */
    /*
      public function findByExampleField($value)
      {
      return $this->createQueryBuilder('l')
      ->andWhere('l.exampleField = :val')
      ->setParameter('val', $value)
      ->orderBy('l.id', 'ASC')
      ->setMaxResults(10)
      ->getQuery()
      ->getResult()
      ;
      }
     */

    /*
      public function findOneBySomeField($value): ?LoginHistory
      {
      return $this->createQueryBuilder('l')
      ->andWhere('l.exampleField = :val')
      ->setParameter('val', $value)
      ->getQuery()
      ->getOneOrNullResult()
      ;
      }
     */
    public function findLoginHistoryByID(int $userID, int $recordsLimit = 20) {
        $query = $this->getEntityManager()->createQueryBuilder();

        $query
                ->select('db.ip_address, db.created, db.logged')
                ->from(LoginHistory::class, 'db')
                ->where('db.user = :userID')
                ->orderBy('db.created', 'DESC')
                ->setParameter(':userID', $userID, \PDO::PARAM_INT)
                ->setMaxResults($recordsLimit)
        ;

        return $query->getQuery()->getResult();
    }

    public function insertLoginHistory(array $loginDataset) {
        $userLogginIn = $this->getEntityManager()->getRepository(Administrator::class)->findOneBy(['email' => $loginDataset['loginUserName']]);

        if (isset($userLogginIn)) {
            try {
                $newLoginHistory = new LoginHistory();

                $newLoginHistory
                        ->setUser($userLogginIn)
                        ->setLogged($loginDataset['loginResult'])
                        ->setIpAddress($loginDataset['loginIpAddress'])
                        ->setCreated(new \Datetime('now'))
                ;

                $this->getEntityManager()->persist($newLoginHistory);
                $this->getEntityManager()->flush();
                return true;
            } catch (Exception $ex) {
                return false;
            }
        }
    }

}
