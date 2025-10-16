<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'inditifiant'=>1
        ]);
    }

     #[Route('/hello','hello')]
    public function hello(): Response{ 
        return new Response(content :"Hello 3A26");

    }
         #[Route('/contact/{tel}','contact')]
    public function contact($tel): Response{ 
       return $this->render('home/contact.html.twig',['telphone'=>$tel]);

    }
    #[Route('/show','show')]
    public function show(): Response{ 
      return new Response(content :"Hi welcome");

    }
   #[Route('/afficher/{name}','afficher')]
    public function afficher($name): Response{ 
       return $this->render('home/affiche.html.twig',['name'=>$name]);

    }
    



}
