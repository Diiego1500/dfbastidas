<?php

namespace App\Repository;

use App\Entity\Purchasedservices;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Purchasedservices|null find($id, $lockMode = null, $lockVersion = null)
 * @method Purchasedservices|null findOneBy(array $criteria, array $orderBy = null)
 * @method Purchasedservices[]    findAll()
 * @method Purchasedservices[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PurchasedservicesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Purchasedservices::class);
    }

    // /**
    //  * @return Purchasedservices[] Returns an array of Purchasedservices objects
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
    public function findOneBySomeField($value): ?Purchasedservices
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
