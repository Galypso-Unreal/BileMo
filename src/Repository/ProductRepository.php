<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query as ORMQuery;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findAllWithPagination($page, $limit) {
        $query = $this->createQueryBuilder('p')
            ->select('p.id, p.model, p.brand, p.color')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);
        return $query->getQuery()->getResult(ORMQuery::HYDRATE_ARRAY);
    }

    public function findAllProducts() {
        $query = $this->createQueryBuilder('p')
            ->select('p.id, p.model, p.brand, p.color');
        return $query->getQuery()->getResult(ORMQuery::HYDRATE_ARRAY);
    }

       public function findById($value): array
       {
           return $this->createQueryBuilder('p')
               ->andWhere('p.id = :val')
               ->setParameter('val', $value)
               ->getQuery()
               ->getOneOrNullResult(ORMQuery::HYDRATE_ARRAY)
           ;
       }

//    /**
//     * @return Product[] Returns an array of Product objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
