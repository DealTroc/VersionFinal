<?php

namespace App\Controller\Mobile;

use App\Entity\Saved;
use App\Repository\SavedRepository;
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
 * @Route("/mobile/saved")
 */
class SavedMobileController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     */
    public function index(SavedRepository $savedRepository, UtilisateurRepository $utilisateurRepository): Response
    {
        $saveds = $savedRepository->findAll();

        $savedsArray = [];

        $i = 0;
        foreach ($saveds as $saved) {
            $savedsArray[$i] = $saved->jsonSerialize();
            $i++;
        }

        if ($savedsArray) {
            return new JsonResponse($savedsArray, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/add", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $saved = new Saved();

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

        $saved->constructor(
            $imageFileName,
            $request->get("description"),
            $request->get("titre"),
            $request->get("categorie"),
            $request->get("prix")
        );

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($saved);
        $entityManager->flush();

        return new JsonResponse($saved, 200);


    }

    /**
     * @Route("/edit", methods={"POST"})
     */
    public function edit(Request $request, SavedRepository $savedRepository): Response
    {
        $saved = $savedRepository->find((int)$request->get("id"));

        if (!$saved) {
            return new JsonResponse(null, 404);
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

        $saved->constructor(
            $imageFileName,
            $request->get("description"),
            $request->get("titre"),
            $request->get("categorie"),
            $request->get("prix")
        );

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($saved);
        $entityManager->flush();

        return new JsonResponse($saved, 200);
    }

    /**
     * @Route("/delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, SavedRepository $savedRepository): JsonResponse
    {
        $saved = $savedRepository->find((int)$request->get("id"));

        if (!$saved) {
            return new JsonResponse(null, 200);
        }

        $entityManager->remove($saved);
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
