<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavorieController extends AbstractController
{
    #[Route('/favorie', name: 'app_favorie')]
    public function index(): Response
    {
        return $this->render('favorie/index.html.twig', [
            'controller_name' => 'FavorieController',
        ]);
    }
}
