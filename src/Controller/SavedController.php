<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Saved;
use App\Repository\ProduitRepository;
use App\Repository\SavedRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/Saved')]
class SavedController extends AbstractController
{

 
    #[Route('/', name: 'app_Saved_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('Saved/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }
    #[Route('/savedList', name: 'app_Saved_saved_index', methods: ['GET'])]
    public function saved(SavedRepository $savedRepository): Response
    {
        return $this->render('fav.html.twig', [
            'produits' => $savedRepository->findAll(),
        ]);
    }
     

    #[Route('/new/{id}', name: 'app_Saved_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SavedRepository $SavedRepository
     ,Produit $produit
    ): Response
    {
        
        $saved = $SavedRepository->findOneByTitle($produit->getTitre());
         
         if($saved ==null){
            $s = new Saved();
            $s->setCategorie($produit->getCategorie());
            $s->setImage($produit->getImage());
            $s->setDescription($produit->getDescription());
            $s->setTitre($produit->getTitre());
           $s->setPrix($produit->getPrix());
             
            $SavedRepository->save($s , true);
             return $this->render('fav.html.twig', [
                'produits' => $SavedRepository->findAll(),
            ]);
        } else {
        return $this->render('fav.html.twig', [
            'produits' => $SavedRepository->findAll(),
        ]);
    }
        
    }

    #[Route('/{id}/edit', name: 'app_Saved_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        $form = $this->createForm(Produit2Type::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $produitRepository->save($produit, true);

            return $this->redirectToRoute('app_Saved_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    } 
    #[Route('/delete/{id}', name: 'app_Saved_delete', methods: ['POST' , 'GET'])]
    public function delete(Request $request, Saved $Saved, SavedRepository $SavedRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$Saved->getId(), $request->request->get('_token'))) {
            $SavedRepository->remove($Saved, true);
        }
     
        return $this->redirectToRoute('app_Saved_saved_index', [], Response::HTTP_SEE_OTHER);
    }
}
