<?php

namespace App\Repository;

use App\Entity\OptionList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OptionList>
 *
 * @method OptionList|null find($id, $lockMode = null, $lockVersion = null)
 * @method OptionList|null findOneBy(array $criteria, array $orderBy = null)
 * @method OptionList[]    findAll()
 * @method OptionList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OptionListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OptionList::class);
    }

//    /**
//     * @return OptionList[] Returns an array of OptionList objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?OptionList
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
