<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query as ORMQuery;

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
            ->getResult(ORMQuery::HYDRATE_ARRAY);
    }

    public function findAllWithPagination($page, $limit,$value1)
    {
        $query = $this->createQueryBuilder('u')
            ->select('u.id, u.email')
            ->andWhere('u.customer = :val1')
            ->setParameter('val1', $value1)
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);
        return $query->getQuery()->getResult(ORMQuery::HYDRATE_ARRAY);
    }

    public function findById($value, $value2): array
    {
        return $this->createQueryBuilder('u')
            ->select('u.id,u.firstname,u.lastname,u.email')
            ->where('u.id = :val')
            ->setParameter('val', $value)
            ->andWhere('u.customer = :val2')
            ->setParameter('val2', $value2)
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult(ORMQuery::HYDRATE_ARRAY);
    }

    public function findOneById($value, $value2): ?User
    {
        return $this->createQueryBuilder('u')
            ->where('u.id = :val')
            ->setParameter('val', $value)
            ->andWhere('u.customer = :val2')
            ->setParameter('val2', $value2)
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getOneOrNullResult();
    }
}
