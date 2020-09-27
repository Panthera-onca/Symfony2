<?php


namespace App\Repository;


use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    public function findListSorties()
    {
        return $this->createQueryBuilder('i')
            ->addSelect('name')
            ->where('i.dateLimiteInscription = true')
            ->join('i.status', 'name')
            ->orderBy('i.dateHeureDebut', 'DESC')
            ->setMaxResults(50)
            ->getQuery()
            ->getResult()
            ;
    }


}
