<?php

namespace App\Controller\Mobile;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use App\Repository\UtilisateurRepository;
use DateTime;
use Exception;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mobile/produit")
 */
class ProduitMobileController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     */
    public function index(ProduitRepository $produitRepository, UtilisateurRepository $utilisateurRepository): Response
    {
        $produits = $produitRepository->findAll();

        $produitsArray = [];

        $i = 0;
        foreach ($produits as $produit) {
            $produitsArray[$i] = $produit->jsonSerialize();
            $produitsArray[$i]["utilisateur"] = $utilisateurRepository->find($produit->getIdUtilisateur());
            $i++;
        }

        if ($produitsArray) {
            return new JsonResponse($produitsArray, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/add", methods={"POST"})
     */
    public function add(Request $request, UtilisateurRepository $utilisateurRepository): JsonResponse
    {
        $produit = new Produit();


        $utilisateur = $utilisateurRepository->find((int)$request->get("utilisateur"));
        if (!$utilisateur) {
            return new JsonResponse("utilisateur with id " . (int)$request->get("utilisateur") . " does not exist", 203);
        }


        $file = $request->files->get("file");
        if ($file) {
            $imageFileName = md5(uniqid()) . '.' . $file->guessExtension();
            try {
                $file->move($this->getParameter('brochures_directory'), $imageFileName);
            } catch (FileException $e) {
                dd($e);
            }
        } else {
            if ($request->get("image")) {
                $imageFileName = $request->get("image");
            } else {
                $imageFileName = "null";
            }
        }

        $produit->constructor(
            $imageFileName,
            $request->get("description"),
            $request->get("titre"),
            $request->get("categorie"),
            $request->get("prix"),
            $utilisateur
        );

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($produit);
        $entityManager->flush();


        $email = $utilisateur->getAdresseemail();
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            try {
                $transport = new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl');
                $transport->setUsername('app.esprit.pidev@gmail.com')->setPassword('dqwqkdeyeffjnyif');
                $mailer = new Swift_Mailer($transport);
                $message = new Swift_Message('Notification');
                $message->setFrom(array('app.esprit.pidev@gmail.com' => 'Notification'))
                    ->setTo(array($email))
                    ->setBody("<h1>Produit ajouté</h1>", 'text/html');
                $mailer->send($message);
            } catch (Exception $exception) {
                return new JsonResponse(null, 405);
            }
        }




        return new JsonResponse($produit, 200);


    }

    /**
     * @Route("/edit", methods={"POST"})
     */
    public function edit(Request $request, ProduitRepository $produitRepository, UtilisateurRepository $utilisateurRepository): Response
    {
        $produit = $produitRepository->find((int)$request->get("id"));

        if (!$produit) {
            return new JsonResponse(null, 404);
        }


        $utilisateur = $utilisateurRepository->find((int)$request->get("utilisateur"));
        if (!$utilisateur) {
            return new JsonResponse("utilisateur with id " . (int)$request->get("utilisateur") . " does not exist", 203);
        }


        $file = $request->files->get("file");
        if ($file) {
            $imageFileName = md5(uniqid()) . '.' . $file->guessExtension();
            try {
                $file->move($this->getParameter('brochures_directory'), $imageFileName);
            } catch (FileException $e) {
                dd($e);
            }
        } else {
            if ($request->get("image")) {
                $imageFileName = $request->get("image");
            } else {
                $imageFileName = "null";
            }
        }

        $produit->constructor(
            $imageFileName,
            $request->get("description"),
            $request->get("titre"),
            $request->get("categorie"),
            $request->get("prix"),
            $utilisateur
        );

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($produit);
        $entityManager->flush();

        return new JsonResponse($produit, 200);
    }

    /**
     * @Route("/delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, ProduitRepository $produitRepository): JsonResponse
    {
        $produit = $produitRepository->find((int)$request->get("id"));

        if (!$produit) {
            return new JsonResponse(null, 200);
        }

        $entityManager->remove($produit);
        $entityManager->flush();

        return new JsonResponse([], 200);
    }


    /**
     * @Route("/image/{image}", methods={"GET"})
     */
    public function getPicture(Request $request): BinaryFileResponse
    {
        return new BinaryFileResponse(
            $this->getParameter('brochures_directory') . "/" . $request->get("image")
        );
    }

}
