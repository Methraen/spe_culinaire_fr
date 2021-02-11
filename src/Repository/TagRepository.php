<?php

namespace App\Repository;

use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tag[]    findAll()
 * @method Tag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    /**
     * @return Tag[] Returns an array of Tag objects
     */
    public function findByLibelle($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.libelle LIKE :val')
            ->setParameter('val', '%'.$value.'%')
            ->orderBy('t.libelle', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOneByLibelle($value): ?Tag
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.libelle = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
