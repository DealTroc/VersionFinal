<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Form\Produit2Type;
use App\Repository\ProduitRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\TexterInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

#[Route('/produit')]
class ProduitController extends AbstractController
{
      //
      private $ProduitRepository;
      private $flashMessage;
    
  
      public function __construct(
          ProduitRepository $ProduitRepository,
          FlashBagInterface $flashMessage,
         
    
      ) {
          $this->ProduitRepository = $ProduitRepository;
          $this->flashMessage = $flashMessage;
        
      }
  
  //
  
    #[Route('/', name: 'app_produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('list.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }



#[Route('/admin', name: 'app_produit_admin_index', methods: ['GET'])]
    public function adminIndex(ProduitRepository $produitRepository , Request $request, PaginatorInterface $paginator): Response
    {
        $produit = $this->ProduitRepository->findAll();
        $pagination = $paginator->paginate(
            $produit,
            $request->query->getInt('page', 1),
            3
        );
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
            'pagination' => $pagination,
        ]);
    }
    

    #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProduitRepository $produitRepository
    ,  SluggerInterface $slugger
    ): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { 
              /** @var UploadedFile $brochureFile */
              $brochureFile = $form->get('image')->getData();

                if ($brochureFile) {
                  $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                   $safeFilename = $slugger->slug($originalFilename);
                  $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();
  
                   try {
                      $brochureFile->move(
                          $this->getParameter('brochures_directory'),
                          $newFilename
                      );
                  } catch (FileException $e) {
                      // ... handle exception if something happens during file upload
                  }
  
                  // updates the 'brochureFilename' property to store the PDF file name
                  // instead of its contents
                  $produit->setImage($newFilename);
              }
  
            $produitRepository->save($produit, true);
            $this->flashMessage->add("success", "Produit ajoutée !");
           
            return $this->redirectToRoute('app_produit_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request,
                         Produit $produit,
                         SluggerInterface $slugger,
                         ProduitRepository $produitRepository,
                         MailerInterface $mailer,
                            UtilisateurRepository  $userRepository,
                         TexterInterface $texter

    ): Response
    {

        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             /** @var UploadedFile $brochureFile */
             $brochureFile = $form->get('image')->getData();
             if ($brochureFile) {
               $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
               $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                try {
                   $brochureFile->move(
                       $this->getParameter('brochures_directory'),
                       $newFilename
                   );
               } catch (FileException $e) {
                   // ... handle exception if something happens during file upload
               }

               // updates the 'brochureFilename' property to store the PDF file name
               // instead of its contents
               $produit->setImage($newFilename);
                  $produitRepository->save($produit, true);
                  $users = $userRepository->findAll();
                  if ($produit->getDiscount()>0){
                  foreach ($users as $u){
                         $sms = new SmsMessage(
                         // the phone number to send the SMS message to
                          '+216'.$u->getNumero(),
                          // the message
                          '\n \n \n A new DISCOUNT ON '.$produit->getTitre()
                      );

                      $sentMessage = $texter->send($sms);
                      $email = (new Email())
                          ->from('symfonyttester@gmail.com')
                          ->to($u->getAdresseemail())
                          ->subject('Discount on '.$produit->getTitre())
                          ->html('<p>We  want to let you know  that we make a new discount on  '.$produit->getTitre().' <br> go check it!</p>');
                      $mailer->send($email);
                    }
                  }
           }
           $this->flashMessage->add("success", "Produit modifiée !");
            
            return $this->redirectToRoute('app_produit_admin_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getIdproduit(), $request->request->get('_token'))) {
            $produitRepository->remove($produit, true);
        }
        $this->flashMessage->add("success", "Produit supprimée !");
        
        return $this->redirectToRoute('app_produit_admin_index', [], Response::HTTP_SEE_OTHER);
    }




}
