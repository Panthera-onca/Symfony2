<?php

namespace App\Repository;

use App\Entity\Lieu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Lieu|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lieu|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lieu[]    findAll()
 * @method Lieu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LieuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lieu::class);
    }

    /**
      * @return Lieu[] Returns an array of Lieu objects
      */

    public function findAllLieu($value): ?array
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.name = :val')
            ->setParameter('val', $value)
            ->orderBy('l.name', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }


}