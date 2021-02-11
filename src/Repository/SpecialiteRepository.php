<?php

namespace App\Repository;

use App\Entity\Specialite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Specialite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Specialite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Specialite[]    findAll()
 * @method Specialite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpecialiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Specialite::class);
    }

    /**
     * @return Specialite[] Returns an array of Specialite objects
     */
    public function findByLibelle($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.libelle LIKE :val')
            ->setParameter('val', '%'.$value.'%')
            ->orderBy('s.libelle', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOneByLibelle($value): ?Specialite
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.libelle = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
