<?php

namespace App\Controller;

use App\Form\FactureType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\FactureRepository;
use App\Repository\UserRepository;
use App\Repository\CommandeRepository;
use App\Repository\LigneFactureRepository;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use App\Entity\Facture;
use App\Entity\Utilisateur;
use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;

use Endroid\QrCode\Builder\BuilderRegistryInterface;
use BaconQrCode\Renderer\Image\Png;
use BaconQrCode\Writer;

use BaconQrCode\Renderer\ImageRenderer;
use Dompdf\Dompdf;
use TCPDF;
use Mpdf\Mpdf;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\TexterInterface;
use Knp\Component\Pager\PaginatorInterface;















class FactureController extends AbstractController
{

    private $FactureRepository;
    private $LigneFactureRepository;
    private $CommandeRepository;
    private $UserRepository;

    private $flashMessage;


    public function __construct(
        FactureRepository $FactureRepository,
        LigneFactureRepository $LigneFactureRepository,
        CommandeRepository $CommandeRepository,
       
    
        FlashBagInterface $flashMessage,
        private BuilderRegistryInterface $builderRegistry


    ) {
        $this->FactureRepository = $FactureRepository;
        $this->LigneFactureRepository = $LigneFactureRepository;
        $this->CommandeRepository = $CommandeRepository;
       
       
        $this->flashMessage = $flashMessage;
    }





    #[Route('/facture', name: 'app_facture')]
    public function index(EntityManagerInterface $entityManager, Request $request, PaginatorInterface $paginator): Response
    {
        $notification_count = 0;
        $message = 'facture payés';
        // Récupérer les commandes en attente avec une date de livraison passée
        $query = $entityManager->createQuery(
            'SELECT c, f.idFacture as fid FROM App\Entity\Commande c
    JOIN c.factures f
    WHERE c.dateLivraison < :now
    AND f.statut = :statut'
        )->setParameter('now', new \DateTime())
            ->setParameter('statut', 'En attente');

        $results = $query->getScalarResult();

        $factureIds = array();
        foreach ($results as $row) {
            $factureIds[] = $row['fid'];
        }



        // Si la requête renvoie des résultats, créer un message de notification
        if (!empty($results)) {
            $notification_count = 1;
            $count = count($results);
            $factureMsg = ($count > 1) ? 'Factures n° ' : 'Facture n° ';
            $factureMsg .= implode(', ', $factureIds) . ' sont payées !';
            // $message = 'Il y a ' . $count . ' commandes en attente dont la date de livraison est passée.';
            $message = 'Les factures suivantes ont été payées : ' . implode(', ', $factureIds);
            //  $this->addFlash('warning', $message);
            $this->addFlash('warning', $factureMsg);
            // Envoyer également une notification par e-mail à l'administrateur
        }

        $factures = $this->FactureRepository->findAll();
        $commandes = $this->CommandeRepository->findAll();
        $nbrv=$this->LigneFactureRepository->countRevenu();
       // $nbus=$this->UserRepository->countUsers();
        $nbf=$this->FactureRepository->countFactures();
        $pagination = $paginator->paginate(
            $factures,
            $request->query->getInt('page', 1),
            5
        );
        $pagination2 = $paginator->paginate(
            $commandes,
            $request->query->getInt('page', 1),
            5
        );
        
        return $this->render('facture/index.html.twig', [
            'controller_name' => 'FactureController',  'pagination' => $pagination,'pagination2' => $pagination2,
            "factures" => $factures, 'commandes' => $commandes, 'notification_count' => $notification_count, 'msg' => $message,'nbrv'=> $nbrv,'nbf'=> $nbf
        ]);
    }

    /**
     * @Route("/facture/{id}", name="facture_show")
     */

    public function showFacture($id)

    {
        $notification_count = 1;
        $message = 'facture payés';
        $facture = $this->FactureRepository->find($id);
      
        return $this->render('facture/show.html.twig', [
            "facture" => $facture, 'notification_count' => $notification_count, 'msg' => $message
        ]);
    }


    /**
     * @Route("/create/facture/{id}", name="facture_create")
     */
    public function createFacture(Request $request, $id)
    {
        $notification_count = 1;
        $message = 'facture payés';
        $commande = $this->CommandeRepository->find($id);
        $facture = new Facture();

        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $facture = $form->getData();


            $facture->setCommande($commande);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($facture);
            $entityManager->flush();
            $this->flashMessage->add("success", "Facture ajoutée !");
            return $this->redirectToRoute('app_facture');
        }

        return $this->render('facture/add.html.twig', [
            'form' => $form->createView(), 'commande' => $commande, 'notification_count' => $notification_count, 'msg' => $message
        ]);
    }












    /**
     * @Route("/edit/facture/{id}", name="facture_edit")
     */

    public function editFacture(Facture $facture, Request $request,
    TexterInterface $texter,$id,MailerInterface $mailer)
    {
        $notification_count = 1;
        $message = 'facture payés';
        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $facture = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($facture);
            $entityManager->flush();
            $this->flashMessage->add("success", "Facture modifiée !");
$u =new Utilisateur ();
           // $users = $userRepository->findAll();
           
           
               

                //$sentMessage = $texter->send($sms);

                $email = (new Email())
                ->from('raniabrahmi2607@gmail.com')
                ->to('raniaa.brahmi@gmail.com')
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject('Time for Symfony Mailer!')
                ->text('Sending emails is fun again!')
                ->html('<p>See Twig integration for better HTML integration!</p>');
    
            $mailer->send($email);
            $this->FactureRepository->sms($facture);

            return $this->redirectToRoute('app_facture');
        }

        return $this->render('facture/edit.html.twig', [
            'form' => $form->createView(), 'notification_count' => $notification_count, 'msg' => $message
        ]);
    }





    /**
     * @Route("/delete/facture/{id}", name="facture_delete")
     */

    public function deleteFacture(Facture $facture)
    {

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($facture);
        $entityManager->flush();
        $this->flashMessage->add("success", "Facture supprimée !");
        
        return $this->redirectToRoute('app_facture');
    }




    /**
     * @Route("/Mesfactures/{id}", name="mesfactures_show")
     */

    public function showFactures($id)


    {
        $message = 'facture payés';
        $notification_count = 1;
        $commandes = $this->CommandeRepository->findBy(array('idUtilisateur' => $id), array('dateLivraison' => 'DESC'));
        $commandesA = $this->CommandeRepository->findBy(array('idUtilisateura' => $id), array('dateLivraison' => 'DESC'));

        // Création d'un tableau pour stocker les identifiants des commandes trouvées
        $idCommandes = array();
        $idCommandesA = array();


        // Récupération des identifiants de commande pour chaque commande trouvée
        foreach ($commandes as $commande) {
            $idCommandes[] = $commande->getIdCommande();
        }
        foreach ($commandesA as $commandeA) {
            $idCommandesA[] = $commandeA->getIdCommande();
        }





        // Récupération des factures correspondantes aux identifiants de commande trouvés
        $factures = $this->FactureRepository->findBy(array('commande' => $idCommandes));
        $facturesA = $this->FactureRepository->findBy(array('commande' => $idCommandesA));




        return $this->render('home/historique.html.twig', [
            "factures" => $factures, "facturesA" => $facturesA, 'notification_count' => $notification_count, 'msg' => $message
        ]);
    }








    /**
     * @Route("/Mafacture/{id}", name="facture_showU")
     */


    public function showFactureU($id)
    {
        // Récupération des données de la facture
        $facture = $this->FactureRepository->findOneBy(['commande' => $id]);
        $ligne = $this->LigneFactureRepository->findOneBy(['facture' => $facture->getIdFacture()]);


        // Génération du QR code
        //$renderer = new Png();
        //$renderer->setWidth(250);
        //$renderer->setHeight(250);
        //$writer = new Writer($renderer);
        //$qrCode = $writer->writeString($facture->getIdFacture());


        // Renvoi des données à la vue
        return $this->render('home/showU.html.twig', [
            'ligne' => $ligne,

            'notification_count' => 1,
            'msg' => 'facture payés',
        ]);
    }













    /**
     * @Route("/notifications", name="notifications")
     */
    public function dashboard(EntityManagerInterface $entityManager)
    {
        // Récupérer les commandes en attente avec une date de livraison passée
        $query = $entityManager->createQuery(
            'SELECT c, f FROM App\Entity\Commande c
            JOIN c.factures f
            WHERE c.dateLivraison < :now
            AND f.statut = :statut'
        )->setParameter('now', new \DateTime())
            ->setParameter('statut', 'En attente');

        $result = $query->getResult();

        // Si la requête renvoie des résultats, créer un message de notification
        if (!empty($result)) {
            $message = 'Il y a ' . count($result) . ' commandes en attente dont la date de livraison est passée.';
            $this->addFlash('warning', $message);
            // Envoyer également une notification par e-mail à l'administrateur
        }

        $factures = $this->FactureRepository->findAll();
        $commandes = $this->CommandeRepository->findAll();
        return $this->render('facture/index.html.twig', [
            'controller_name' => 'FactureController',
            "factures" => $factures, 'commandes' => $commandes
        ]);
    }

    /**
     * @Route("/invoice-print.html", name="print")
     */
    public function print()
    {

        return $this->render('home/invoice-print.html');
    }









    
    
    
    
    
    
    
    











}
