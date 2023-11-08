<?php

namespace App\Repository;

use App\Entity\PhotoGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PhotoGroup>
 *
 * @method PhotoGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhotoGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhotoGroup[]    findAll()
 * @method PhotoGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhotoGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PhotoGroup::class);
    }

//    /**
//     * @return PhotoGroup[] Returns an array of PhotoGroup objects
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

//    public function findOneBySomeField($value): ?PhotoGroup
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
