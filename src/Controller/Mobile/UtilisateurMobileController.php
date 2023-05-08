<?php

namespace App\Controller\Mobile;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
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
 * @Route("/mobile/utilisateur")
 */
class UtilisateurMobileController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     */
    public function index(UtilisateurRepository $utilisateurRepository): Response
    {
        $utilisateurs = $utilisateurRepository->findAll();

        if ($utilisateurs) {
            return new JsonResponse($utilisateurs, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/add", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $utilisateur = new Utilisateur();


        $utilisateur->constructor(
            $request->get("email")
        );

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($utilisateur);
        $entityManager->flush();

        return new JsonResponse($utilisateur, 200);


    }

    /**
     * @Route("/edit", methods={"POST"})
     */
    public function edit(Request $request, UtilisateurRepository $utilisateurRepository): Response
    {
        $utilisateur = $utilisateurRepository->find((int)$request->get("id"));

        if (!$utilisateur) {
            return new JsonResponse(null, 404);
        }


        $utilisateur->constructor(
            $request->get("email")
        );

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($utilisateur);
        $entityManager->flush();

        return new JsonResponse($utilisateur, 200);
    }

    /**
     * @Route("/delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, UtilisateurRepository $utilisateurRepository): JsonResponse
    {
        $utilisateur = $utilisateurRepository->find((int)$request->get("id"));

        if (!$utilisateur) {
            return new JsonResponse(null, 200);
        }

        $entityManager->remove($utilisateur);
        $entityManager->flush();

        return new JsonResponse([], 200);
    }


}
