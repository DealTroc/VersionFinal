<?php

namespace App\Controller;

use App\Form\LigneFactureType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\LigneFactureRepository;
use App\Repository\FactureRepository;
use App\Repository\ProduitRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use App\Entity\LigneFacture;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;


class LigneFactureController extends AbstractController
{



    private $LigneFactureRepository;
    private $FactureRepository;
    private $ProduitRepository;
    private $UserRepository;
    private $flashMessage;

    public function __construct(
        LigneFactureRepository $LigneFactureRepository,
        FactureRepository $FactureRepository,
        ProduitRepository $ProduitRepository,
        FlashBagInterface $flashMessage
    ) {
        $this->LigneFactureRepository = $LigneFactureRepository;
        $this->FactureRepository = $FactureRepository;
        $this->ProduitRepository = $ProduitRepository;
        $this->flashMessage = $flashMessage;
    }




    #[Route('/ligne/facture', name: 'app_ligne_facture')]
    public function index(): Response
    {$notification_count=0;
        $message='facture payés';
        $lignesfactures = $this->LigneFactureRepository->findAll();
        $factures = $this->FactureRepository->findAll();
        return $this->render('ligne_facture/index.html.twig', [
            'controller_name' => 'LigneFactureController',
            "lignesfactures" => $lignesfactures, 'factures' => $factures,'notification_count'=>$notification_count,'msg'=> $message
        ]);
    }

    /**
     * @Route("/ligne/{id}", name="lignefacture_show")
     */

    public function showLigneFacture($id)
    {$notification_count=0;
        $message='facture payés';
        $lignefacture = $this->LigneFactureRepository->find($id);
        return $this->render('ligne_facture/show.html.twig', [
            "lignefacture" => $lignefacture,'notification_count'=>$notification_count,'msg'=> $message
        ]);
    }


    /**
     * @Route("/create/ligne/{id}", name="lignefacture_create")
     */
    public function createLigneFacture(Request $request, $id)
    {
        $notification_count=0;
        $message='facture payés';
        $facture = $this->FactureRepository->find($id);
        $lignefacture = new LigneFacture();


        //recherche du prix initial 

        $id_produit = $facture->getCommande()->getIdProduit();
        $prix_initial = $this->ProduitRepository->find($id_produit)->getPrix();




       


      
        $form = $this->createForm(LigneFactureType::class, $lignefacture);
     
       



        $form->handleRequest($request);
        $lignefacture->setFacture($facture);
        $lignefacture->setPrixInitial($prix_initial);

        if ($form->isSubmitted() && $form->isValid()) {

            $lignefacture = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($lignefacture);
            $entityManager->flush();
            $this->flashMessage->add("success", "Ligne Facture ajoutée !");
            return $this->redirectToRoute('app_ligne_facture');
        }

        return $this->render('ligne_facture/add.html.twig', [
            'form' => $form->createView(), 'facture' => $facture,'prixInitial'=>$prix_initial,'notification_count'=>$notification_count,'msg'=> $message
        ]);
    }












    /**
     * @Route("/edit/ligne/{id}", name="lignefacture_edit")
     */

    public function editLigneFacture(LigneFacture $lignefacture, Request $request)
    {

        $notification_count=0;
        $message='facture payés';

        $form = $this->createForm(LigneFactureType::class, $lignefacture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $lignefacture = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($lignefacture);
            $entityManager->flush();
            $this->flashMessage->add("success", "LigneFacture modifiée !");
            return $this->redirectToRoute('app_ligne_facture');
        }

        return $this->render('ligne_facture/edit.html.twig', [
            'form' => $form->createView(),'notification_count'=>$notification_count,'msg'=> $message
        ]);
    }





    /**
     * @Route("/delete/ligne/{id}", name="lignefacture_delete")
     */

    public function deleteLigneFacture(LigneFacture $lignefacture)
    {$notification_count=0;
        $message='facture payés';

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($lignefacture);
        $entityManager->flush();
        $this->flashMessage->add("success", "LigneFacture supprimée !");
        return $this->redirectToRoute('app_ligne_facture');
    }
}
