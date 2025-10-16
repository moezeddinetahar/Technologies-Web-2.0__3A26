<?php

namespace App\Controller;

use App\Entity\Reader;
use App\Form\ReaderType;
use App\Repository\ReaderRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ReaderController extends AbstractController
{
    #[Route('/reader', name: 'app_reader')]
    public function index(): Response
    {
        return $this->render('reader/index.html.twig', [
            'controller_name' => 'ReaderController',
        ]);
    }

    #[Route('/reader/add', name: 'reader_add')]
    public function addReader(Request $request, ManagerRegistry $doctrine): Response
    {
        $reader = new Reader();

        $form = $this->createForm(ReaderType::class, $reader);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $doctrine->getManager();
            $em->persist($reader);
            $em->flush();

            return $this->redirectToRoute('app_readers_list');
        }

        return $this->render('reader/add.html.twig', [
            'formReader' => $form->createView(),
        ]);
    }

    #[Route('/reader/showAll', name: 'app_readers_list')]
    public function listReaders(ReaderRepository $repo): Response
    {
        $readers = $repo->findAll();

        $totalCount = count($readers);

        return $this->render('reader/showAll.html.twig', [
            'readers' => $readers,
            'totalCount' => $totalCount,
        ]);
    }
}
