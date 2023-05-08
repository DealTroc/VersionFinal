<?php

namespace App\Repository;

use App\Entity\LigneFacture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LigneFacture>
 *
 * @method LigneFacture|null find($id, $lockMode = null, $lockVersion = null)
 * @method LigneFacture|null findOneBy(array $criteria, array $orderBy = null)
 * @method LigneFacture[]    findAll()
 * @method LigneFacture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LigneFactureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LigneFacture::class);
    }

    public function save(LigneFacture $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(LigneFacture $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return LigneFacture[] Returns an array of LigneFacture objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?LigneFacture
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }







public function countRevenu(): int
{
    $qb = $this->createQueryBuilder('ligne_facture');

    $qb->select('SUM(ligne_facture.revenu)')
    ;
if($qb->getQuery()->getSingleScalarResult()>0)
    return $qb->getQuery()->getSingleScalarResult();
    else return 0 ;
}


}
