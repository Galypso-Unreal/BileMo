<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

   public function findAllUsers($value): array
   {
       return $this->createQueryBuilder('u')
                  ->select('u.id, u.email')
                  ->andWhere('u.customer = :val')
                  ->setParameter('val', $value)
                  ->orderBy('u.id', 'ASC')
                  ->getQuery()
                  ->getResult()
       ;
   }

   public function findById($value,$value2): array
   {
       return $this->createQueryBuilder('u')
                  ->andWhere('u.id = :val')
                  ->andWhere('u.customer = :val2')
                  ->setParameter('val', $value)
                  ->setParameter('val2', $value2)
                  ->orderBy('u.id', 'ASC')
                  ->getQuery()
                  ->getResult()
       ;
   }
}
