<?php

namespace App\Controller\Mobile;

use App\Entity\Livreur;
use App\Repository\LivreurRepository;
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
 * @Route("/mobile/livreur")
 */
class LivreurMobileController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     */
    public function index(LivreurRepository $livreurRepository): Response
    {
        $livreurs = $livreurRepository->findAll();

        if ($livreurs) {
            return new JsonResponse($livreurs, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/add", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $livreur = new Livreur();


        $livreur->constructor(
            $request->get("nom"),
            $request->get("num")
        );

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($livreur);
        $entityManager->flush();

        return new JsonResponse($livreur, 200);


    }

    /**
     * @Route("/edit", methods={"POST"})
     */
    public function edit(Request $request, LivreurRepository $livreurRepository): Response
    {
        $livreur = $livreurRepository->find((int)$request->get("id"));

        if (!$livreur) {
            return new JsonResponse(null, 404);
        }


        $livreur->constructor(
            $request->get("nom"),
            $request->get("num")
        );

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($livreur);
        $entityManager->flush();

        return new JsonResponse($livreur, 200);
    }

    /**
     * @Route("/delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, LivreurRepository $livreurRepository): JsonResponse
    {
        $livreur = $livreurRepository->find((int)$request->get("id"));

        if (!$livreur) {
            return new JsonResponse(null, 200);
        }

        $entityManager->remove($livreur);
        $entityManager->flush();

        return new JsonResponse([], 200);
    }


}
