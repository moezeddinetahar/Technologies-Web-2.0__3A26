<?php

namespace App\Controller;

use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AutherController extends AbstractController
{
    #[Route('/auther', name: 'app_auther')]
    public function index(): Response
    {
        return $this->render('auther/index.html.twig', [
            'controller_name' => 'AutherController',
        ]);
    }

    #[Route(path: '/showAll', name: 'showAll')]
    public function showAll(AuthorRepository $repo): Response
    {
        $authors = $repo->findAll();
        return $this->render('auth/showAll.html.twig', parameters:['list'=>$authors]);
    }
}
