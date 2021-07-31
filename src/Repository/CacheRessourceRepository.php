<?php

namespace App\Repository;

use App\Entity\CacheRessource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CacheRessource|null find($id, $lockMode = null, $lockVersion = null)
 * @method CacheRessource|null findOneBy(array $criteria, array $orderBy = null)
 * @method CacheRessource[]    findAll()
 * @method CacheRessource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CacheRessourceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CacheRessource::class);
    }

    // /**
    //  * @return CacheRessource[] Returns an array of CacheRessource objects
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
    public function findOneBySomeField($value): ?CacheRessource
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
