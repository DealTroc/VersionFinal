<?php

namespace App\Controller\Mobile;

use App\Entity\Facture;
use App\Repository\FactureRepository;
use App\Repository\CommandeRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mobile/facture")
 */
class FactureMobileController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     */
    public function index(FactureRepository $factureRepository): Response
    {
        $factures = $factureRepository->findAll();

        if ($factures) {
            return new JsonResponse($factures, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/add", methods={"POST"})
     */
    public function add(Request $request, CommandeRepository $commandeRepository): JsonResponse
    {
        $facture = new Facture();


        $commande = $commandeRepository->find((int)$request->get("commande"));
        if (!$commande) {
            return new JsonResponse("commande with id " . (int)$request->get("commande") . " does not exist", 203);
        }


        $facture->constructor(
            DateTime::createFromFormat("d-m-Y", $request->get("dateFacturation")),
            (int)$request->get("commission"),
            $request->get("statut"),
            $commande
        );

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($facture);
        $entityManager->flush();

        return new JsonResponse($facture, 200);


    }

    /**
     * @Route("/edit", methods={"POST"})
     */
    public function edit(Request $request, FactureRepository $factureRepository, CommandeRepository $commandeRepository): Response
    {
        $facture = $factureRepository->find((int)$request->get("id"));

        if (!$facture) {
            return new JsonResponse(null, 404);
        }


        $commande = $commandeRepository->find((int)$request->get("commande"));
        if (!$commande) {
            return new JsonResponse("commande with id " . (int)$request->get("commande") . " does not exist", 203);
        }


        $facture->constructor(
            DateTime::createFromFormat("d-m-Y", $request->get("dateFacturation")),
            (int)$request->get("commission"),
            $request->get("statut"),
            $commande
        );

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($facture);
        $entityManager->flush();

        return new JsonResponse($facture, 200);
    }

    /**
     * @Route("/delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, FactureRepository $factureRepository): JsonResponse
    {
        $facture = $factureRepository->find((int)$request->get("id"));

        if (!$facture) {
            return new JsonResponse(null, 200);
        }

        $entityManager->remove($facture);
        $entityManager->flush();

        return new JsonResponse([], 200);
    }


}
