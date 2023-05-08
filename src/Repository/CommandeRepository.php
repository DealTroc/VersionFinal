<?php

namespace App\Repository;

use App\Entity\Commande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Twilio\Rest\Client;

/**
 * @extends ServiceEntityRepository<Commande>
 *
 * @method Commande|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commande|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commande[]    findAll()
 * @method Commande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

    public function save(Commande $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Commande $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

       
    
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Commande
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function sms(Commande $commande)
    {
        if ($commande->getStatus() == 1) {
        // Your Account SID and Auth Token from twilio.com/console
        $sid = 'ACaad6ca4504de9be059c477447bb59705';
        $auth_token = '8b45a8af138f2a37f8c8c96553d2fbfa';
        // In production, these should be environment variables. E.g.:
        // $auth_token = $_ENV["TWILIO_AUTH_TOKEN"]
        // A Twilio number you own with SMS capabilities
        $twilio_number = "+16814024208";

        $client = new Client($sid, $auth_token);
        $client->messages->create(
            // the number you'd like to send the message to
            '+21620472167',
            [
                // A Twilio phone number you purchased at twilio.com/console
                'from' => '+16814024208',
                // the body of the text message you'd like to send
                'body' => 'Votre commande a été passée avec succées'
            ]
        );
    }}



    public function countCommandesByRole(string $role): int
    {
        $qb = $this->createQueryBuilder('commande');

        $qb->select('COUNT(commande.idCommande)')
        ->where('commande.role = :role')
        ->setParameter('role', $role);

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function countCommandesByStatus(string $status): int
    {
        $qb = $this->createQueryBuilder('commande');

        $qb->select('COUNT(commande.idCommande)')
        ->where('commande.status = :status')
        ->setParameter('status', $status);

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function FindIdLivreur(string $nom): int
    {
        $qb = $this->createQueryBuilder('livreur');

        $qb->select('(livreur.nom)')
        ->where('livreur.nom = :nom')
        ->setParameter('nom', $nom);

        return $qb->getQuery()->getSingleScalarResult();
    }


    public function SortBynomProduit()
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e.nomproduit', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function SortBydescriptionProduit()
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e.description', 'ASC')
            ->getQuery()
            ->getResult();
    }
    public function SortBycategorieProduit()
    {

        return $this->createQueryBuilder('p')
            ->join('p.idcategorie', 'c')
            ->orderBy('c.nomcategorie', 'ASC')
            ->getQuery()
            ->getResult();
    }


}
