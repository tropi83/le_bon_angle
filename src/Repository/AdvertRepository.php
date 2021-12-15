<?php

namespace App\Repository;

use App\Entity\Advert;
use Datetime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use phpDocumentor\Reflection\Types\Integer;

/**
 * @method Advert|null find($id, $lockMode = null, $lockVersion = null)
 * @method Advert|null findOneBy(array $criteria, array $orderBy = null)
 * @method Advert[]    findAll()
 * @method Advert[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdvertRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Advert::class);
    }

    /**
     * @param Datetime $from
     * @return int Returns the number of deletions
     */
    public function deleteRejectedByDate(Datetime $from): int
    {
        $now = new DateTime();
        $qd = $this->createQueryBuilder('a');
        $qd->delete()
            ->where('a.createdAt BETWEEN :from AND :now')
            ->andWhere('a.state = :state ')
            ->setParameter('from', $from )
            ->setParameter('now', $now)
            ->setParameter('state', 'rejected');
        $query = $qd->getQuery();
        return $query->execute();
    }

    /**
     * @param Datetime $from
     * @return int Returns the number of deletions
     */
    public function deletePublishedByDate(Datetime $from): int
    {
        $now = new DateTime();
        $qd = $this->createQueryBuilder('a');
        $qd->delete()
            ->where('a.publishedAt BETWEEN :from AND :now')
            ->andWhere('a.state = :state ')
            ->setParameter('from', $from )
            ->setParameter('now', $now)
            ->setParameter('state', 'published');
        $query = $qd->getQuery();
        return $query->execute();
    }
    // /**
    //  * @return Advert[] Returns an array of Advert objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Advert
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
