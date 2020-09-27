<?php


namespace App\Repository;

use App\Entity\User\User1;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User1|null find($id, $lockMode = null, $lockVersion = null)
 * @method User1|null findOneBy(array $criteria, array $orderBy = null)
 * @method User1[]    findAll()
 * @method User1[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User1::class);
    }

    /**
     * @param $id
     * @return User1[] Returns an array of User1 objects
     */
    public function findByExampleField($id)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.dateCreated = :val')
            ->setParameter('val', $id)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?User1
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
