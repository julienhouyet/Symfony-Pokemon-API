<?php

namespace App\Repository;

use App\Entity\PokemonStat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PokemonStat>
 *
 * @method PokemonStat|null find($id, $lockMode = null, $lockVersion = null)
 * @method PokemonStat|null findOneBy(array $criteria, array $orderBy = null)
 * @method PokemonStat[]    findAll()
 * @method PokemonStat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PokemonStatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PokemonStat::class);
    }

    //    /**
    //     * @return PokemonStat[] Returns an array of PokemonStat objects
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

    //    public function findOneBySomeField($value): ?PokemonStat
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
