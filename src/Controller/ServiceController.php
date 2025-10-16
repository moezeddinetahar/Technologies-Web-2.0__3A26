<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ServiceController extends AbstractController
{
    #[Route('/service', name: 'app_service')]
    public function index(): Response
    {
        return $this->render('service/index.html.twig', [
            'controller_name' => 'ServiceController',
        ]);
    }

      #[Route('/ShowService/{name}','ShowService')]
    public function afficher($name): Response{ 
       return $this->render('home/show.html.twig',['name'=>$name]);

    }
    #[Route('/index', name: 'index')]
    public function gotToIndex(): Response
    {
       return $this->redirectToRoute('app_home');
    }

}
