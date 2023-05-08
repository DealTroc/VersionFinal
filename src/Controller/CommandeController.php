<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use App\Repository\CommandeRepository;
use App\Entity\Commande;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CommandeType;
use DateTime;
use App\Form\CommandeUpdateType;
use App\Repository\ProduitRepository;
use App\Service\CommandeService;
use Knp\Component\Pager\PaginatorInterface;

class CommandeController extends AbstractController
{
    
    private $CommandeRepository;
    private $ProduitRepository;
    private $flashMessage;
  
    

    public function __construct(
        CommandeRepository $CommandeRepository,
        ProduitRepository $ProduitRepository,    
        FlashBagInterface $flashMessage,
        
       
    ) {
        
        $this->CommandeRepository = $CommandeRepository;
        $this->ProduitRepository = $ProduitRepository;
        $this->flashMessage = $flashMessage;
       
    }

  

 #[Route('/commande/choisir', name: 'choisir_commande')]
    public function choisir(): Response
    {
        $commande = new Commande();
        $produits = $this->ProduitRepository->findAll();
        return $this->render('commande/choisir.html.twig', [
            'controller_name' => 'CommandeController',
            "produits" => $produits
        ]);
    }

    #[Route('/commande', name: 'app_commande')]
    public function index(CommandeService $commandeService, Request $request, PaginatorInterface $paginator): Response
    {

       
        $produits = $this->ProduitRepository->findAll();
        $countAchat = $commandeService->countCommandesByRole('achat');
        $countEchange = $commandeService->countCommandesByRole('echange');
        $countStatus =  $commandeService->countCommandesByStatus(0);

        $donnees = $this->CommandeRepository->findAll();
        $pagination = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('commande/index.html.twig', [
            'controller_name' => 'CommandeController',
            "produits" => $produits,
            "countAchat" => $countAchat,
            "countEchange" => $countEchange,
            "countStatus" =>  $countStatus,
            'pagination' => $pagination,
       

        ]);
    }


    /**
     * @Route("/commande/{id}", name="commande_show")
     */

    public function showCommande($id)
    {
        $commandes = $this->CommandeRepository->find($id);
        return $this->render('commande/show.html.twig', [
            "commande" => $commandes
        ]);
    }
    /**
     * @Route("/create/commande/{id}", name="commande_create")
     */
    
    public function createCommande(Request $request, $id)
    {

        $commande = new Commande();
        $commande->setDate(new DateTime());
        $produit = $this-> ProduitRepository->find($id);
        $user=$produit-> getIdUtilisateur();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commande = $form->getData();
            // $mailerService->send("Nouveau Message", "amirabenhenda2s2@gmail.com", "amirabenhenda2s2@gmail.com", "email/contact.html.twig", ["contenu" => $commande->getContenu()]);
            $commande-> setIdProduit($id);
            $commande-> setIdUtilisateur($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commande);
            $entityManager->flush();
            $this->flashMessage->add("success", "Commande ajoutée !");
            return $this->redirectToRoute('app_commande');

        }

        return $this->render('commande/add.html.twig', [
            'form' => $form->createView()
        ]);
    }


    

    /**
     * @Route("/edit/commande/{id}", name="commande_edit")
     */

    public function editCommande(Commande $commande, Request $request, CommandeRepository $commandeRepository)
    {
        $form = $this->createForm(CommandeUpdateType::class, $commande);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $commande = $form->getData();
            $livreur= $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commande);
            $entityManager->flush();
            $this->flashMessage->add("success", "Commande modifiée !");
           // $commandeRepository->sms($commande);
            return $this->redirectToRoute('app_commande');
        }
       
        $this->addFlash('danger', 'reponse envoyée avec succées');

        return $this->render('commande/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/delete/commande/{id}", name="commande_delete")
     */

    public function deleteCommande(Commande $commande)
    {

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($commande);
        $entityManager->flush();
        $this->flashMessage->add("success", "Commande supprimée !");
        return $this->redirectToRoute('app_commande');
    }


}
