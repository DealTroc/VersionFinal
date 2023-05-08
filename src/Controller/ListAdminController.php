<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListAdminController extends AbstractController
{
    #[Route('/list/admin', name: 'app_list_admin')]
    public function index(): Response
    {
        return $this->render('list_admin/index.html.twig', [
            'controller_name' => 'ListAdminController',
        ]);
    }
}
