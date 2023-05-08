<?php

namespace App\Controller\Mobile;

use App\Entity\Commande;
use App\Repository\CommandeRepository;
use App\Repository\UtilisateurRepository;
use App\Repository\ProduitRepository;
use App\Repository\LivreurRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mobile/commande")
 */
class CommandeMobileController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     */
    public function index(CommandeRepository $commandeRepository, UtilisateurRepository $utilisateurRepository, LivreurRepository $livreurRepository, ProduitRepository $produitRepository): Response
    {
        $commandes = $commandeRepository->findAll();

        $commandesArray = [];

        $i = 0;

        foreach ($commandes as $commande) {
            $commandesArray[$i] = $commande->jsonSerialize();
            $commandesArray[$i]["utilisateur"] = $utilisateurRepository->find($commande->getIdUtilisateur());
            $commandesArray[$i]["produit"] = $produitRepository->find($commande->getIdProduit());
            $commandesArray[$i]["livreur"] = $livreurRepository->find($commande->getIdLivreur());

            $i++;
        }

        if ($commandesArray) {
            return new JsonResponse($commandesArray, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/add", methods={"POST"})
     */
    public function add(Request $request, UtilisateurRepository $utilisateurRepository, ProduitRepository $produitRepository, LivreurRepository $livreurRepository): JsonResponse
    {
        $commande = new Commande();


        $utilisateur = $utilisateurRepository->find((int)$request->get("utilisateur"));
        if (!$utilisateur) {
            return new JsonResponse("utilisateur with id " . (int)$request->get("utilisateur") . " does not exist", 203);
        }

        $produit = $produitRepository->find((int)$request->get("produit"));
        if (!$produit) {
            return new JsonResponse("produit with id " . (int)$request->get("produit") . " does not exist", 203);
        }

        $livreur = $livreurRepository->find((int)$request->get("livreur"));
        if (!$livreur) {
            return new JsonResponse("livreur with id " . (int)$request->get("livreur") . " does not exist", 203);
        }

        $email = $utilisateur->getAdresseemail();


        $commande->constructor(
            $utilisateur,
            $produit,
            DateTime::createFromFormat("d-m-Y", $request->get("date")),
            $request->get("role"),
            (int)$request->get("status"),
            $livreur,
            DateTime::createFromFormat("d-m-Y", $request->get("dateLivraison")),
            DateTime::createFromFormat("d-m-Y", $request->get("dateConfirmation"))
        );

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($commande);
        $entityManager->flush();

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            try {
                $transport = new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl');
                $transport->setUsername('app.esprit.pidev@gmail.com')->setPassword('dqwqkdeyeffjnyif');
                $mailer = new Swift_Mailer($transport);
                $message = new Swift_Message('Notification');
                $message->setFrom(array('app.esprit.pidev@gmail.com' => 'Notification'))
                    ->setTo(array($email))
                    ->setBody("<h1>Commande ajouté</h1>", 'text/html');
                $mailer->send($message);
            } catch (Exception $exception) {
                return new JsonResponse(null, 405);
            }
        }


        return new JsonResponse($commande, 200);


    }

    /**
     * @Route("/edit", methods={"POST"})
     */
    public function edit(Request $request, CommandeRepository $commandeRepository, UtilisateurRepository $utilisateurRepository, ProduitRepository $produitRepository, LivreurRepository $livreurRepository): Response
    {
        $commande = $commandeRepository->find((int)$request->get("id"));

        if (!$commande) {
            return new JsonResponse(null, 404);
        }

        $utilisateur = $utilisateurRepository->find((int)$request->get("utilisateur"));
        if (!$utilisateur) {
            return new JsonResponse("utilisateur with id " . (int)$request->get("utilisateur") . " does not exist", 203);
        }

        $produit = $produitRepository->find((int)$request->get("produit"));
        if (!$produit) {
            return new JsonResponse("produit with id " . (int)$request->get("produit") . " does not exist", 203);
        }

        $livreur = $livreurRepository->find((int)$request->get("livreur"));
        if (!$livreur) {
            return new JsonResponse("livreur with id " . (int)$request->get("livreur") . " does not exist", 203);
        }

        $commande->constructor(
            $utilisateur,
            $produit,
            DateTime::createFromFormat("d-m-Y", $request->get("date")),
            $request->get("role"),
            (int)$request->get("status"),
            $livreur,
            DateTime::createFromFormat("d-m-Y", $request->get("dateLivraison")),
            DateTime::createFromFormat("d-m-Y", $request->get("dateConfirmation"))
        );

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($commande);
        $entityManager->flush();

        return new JsonResponse($commande, 200);
    }

    /**
     * @Route("/delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, CommandeRepository $commandeRepository): JsonResponse
    {
        $commande = $commandeRepository->find((int)$request->get("id"));

        if (!$commande) {
            return new JsonResponse(null, 200);
        }

        $entityManager->remove($commande);
        $entityManager->flush();

        return new JsonResponse([], 200);
    }


}
