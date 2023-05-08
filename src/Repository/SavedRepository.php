<?php

namespace App\Repository;

use App\Entity\Saved;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Saved>
 *
 * @method Saved|null find($id, $lockMode = null, $lockVersion = null)
 * @method Saved|null findOneBy(array $criteria, array $orderBy = null)
 * @method Saved[]    findAll()
 * @method Saved[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SavedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Saved::class);
    }

    public function save(Saved $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Saved $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Saved[] Returns an array of Saved objects
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

   public function findOneByTitle($value): ?Saved
     {
         return $this->createQueryBuilder('p')
             ->andWhere('p.titre = :val')
             ->setParameter('val', $value)
             ->getQuery()
             ->getOneOrNullResult()
         ;
     }
}
