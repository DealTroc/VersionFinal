<?php

namespace App\Controller\Mobile;

use App\Entity\LigneFacture;
use App\Repository\LigneFactureRepository;
use App\Repository\FactureRepository;
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
 * @Route("/mobile/ligneFacture")
 */
class LigneFactureMobileController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     */
    public function index(LigneFactureRepository $ligneFactureRepository): Response
    {
        $ligneFactures = $ligneFactureRepository->findAll();

        if ($ligneFactures) {
            return new JsonResponse($ligneFactures, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/add", methods={"POST"})
     */
    public function add(Request $request, FactureRepository $factureRepository): JsonResponse
    {
        $ligneFacture = new LigneFacture();


        $facture = $factureRepository->find((int)$request->get("facture"));
        if (!$facture) {
            return new JsonResponse("facture with id " . (int)$request->get("facture") . " does not exist", 203);
        }


        $ligneFacture->constructor(
            $facture,
            (int)$request->get("prixInitial"),
            (int)$request->get("prixVente"),
            (int)$request->get("prixLivraison"),
            (int)$request->get("prixTotal"),
            (int)$request->get("revenu")
        );

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($ligneFacture);
        $entityManager->flush();

        return new JsonResponse($ligneFacture, 200);


    }

    /**
     * @Route("/edit", methods={"POST"})
     */
    public function edit(Request $request, LigneFactureRepository $ligneFactureRepository, FactureRepository $factureRepository): Response
    {
        $ligneFacture = $ligneFactureRepository->find((int)$request->get("id"));

        if (!$ligneFacture) {
            return new JsonResponse(null, 404);
        }


        $facture = $factureRepository->find((int)$request->get("facture"));
        if (!$facture) {
            return new JsonResponse("facture with id " . (int)$request->get("facture") . " does not exist", 203);
        }


        $ligneFacture->constructor(
            $facture,
            (int)$request->get("prixInitial"),
            (int)$request->get("prixVente"),
            (int)$request->get("prixLivraison"),
            (int)$request->get("prixTotal"),
            (int)$request->get("revenu")
        );

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($ligneFacture);
        $entityManager->flush();

        return new JsonResponse($ligneFacture, 200);
    }

    /**
     * @Route("/delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, LigneFactureRepository $ligneFactureRepository): JsonResponse
    {
        $ligneFacture = $ligneFactureRepository->find((int)$request->get("id"));

        if (!$ligneFacture) {
            return new JsonResponse(null, 200);
        }

        $entityManager->remove($ligneFacture);
        $entityManager->flush();

        return new JsonResponse([], 200);
    }


}
