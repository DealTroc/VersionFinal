<?php

namespace App\Repository;

use App\Entity\Facture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Twilio\Rest\Client;
/**
 * @extends ServiceEntityRepository<Facture>
 *
 * @method Facture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Facture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Facture[]    findAll()
 * @method Facture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FactureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Facture::class);
    }

    public function save(Facture $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Facture $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Facture[] Returns an array of Facture objects
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

//    public function findOneBySomeField($value): ?Facture
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }





public function sms(Facture $Facture)
    {
        
        // Your Account SID and Auth Token from twilio.com/console
        $sid = 'AC6f4b20057358a4624201e8ca5b93b920';
        $auth_token = '2902cb7e6019aede8831451b81e6f3b6';
        // In production, these should be environment variables. E.g.:
        // $auth_token = $_ENV["TWILIO_AUTH_TOKEN"]
        // A Twilio number you own with SMS capabilities
        $twilio_number = "+15856321478";

        $client = new Client($sid, $auth_token);
        $client->messages->create(
            // the number you'd like to send the message to
            '+21652598017',
            [
                // A Twilio phone number you purchased at twilio.com/console
                'from' => '+15856321478',
                // the body of the text message you'd like to send
                'body' => 'La facture n°'.
                $Facture->getIdFacture().'a été modifiée'
            ]
        );
    }



    public function countFactures(): int
    {
        $qb = $this->createQueryBuilder('facture');

        $qb->select('COUNT(facture.idFacture)')
        ;
if($qb->getQuery()->getSingleScalarResult()>0)
        return $qb->getQuery()->getSingleScalarResult();
        else 
        return 0 ;
    }





}
