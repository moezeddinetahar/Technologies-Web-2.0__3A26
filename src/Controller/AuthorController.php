<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Author;
use App\Form\AuthorType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

final class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }
    #[Route('/auth/{name}', name: 'ShowAuthor')]
    public function ShowAuthor($name): Response
    {

        return $this->render('author/Show.html.twig', ['nom' => $name]);
    }



    #[Route('/authors', name: 'app_list_authors')]
    public function listAuthors(): Response
    {
        $authors = [
            ['id' => 1, 'picture' => '/images/gettyimages-89861094.jpg', 'username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com', 'nb_books' => 100],
            ['id' => 2, 'picture' => '/images/images.jpg', 'username' => 'William Shakespeare', 'email' => 'william.shakespeare@gmail.com', 'nb_books' => 200],
            ['id' => 3, 'picture' => '/images/téléchargé (2).jpg', 'username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300],
        ];

        return $this->render('author/list.html.twig', [
            'authors' => $authors,
        ]);
    }

    #[Route('/author/details/{id}', name: 'app_author_details')]
    public function authorDetails(int $id): Response
    {
        $authors = [
            1 => ['id' => 1, 'picture' => '/images/Victor-Hugo.jpg', 'username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com', 'nb_books' => 100],
            2 => ['id' => 2, 'picture' => '/images/william-shakespeare.jpg', 'username' => 'William Shakespeare', 'email' => 'william.shakespeare@gmail.com', 'nb_books' => 200],
            3 => ['id' => 3, 'picture' => '/images/Taha_Hussein.jpg', 'username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300],
        ];

        $author = $authors[$id] ?? null;

        return $this->render('author/showAuthor.html.twig', [
            'author' => $author,
        ]);
    }

    #[Route('/showAll', name: 'showAll')]
    public function showAll(AuthorRepository $repo)
    {
        $authors = $repo->findAll();
        return $this->render('author/showAll.html.twig', [
            'authors' => $authors
        ]);
    }
    #[Route("/add", "add")]
    public function add(ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        $author = new Author();
        $author->setEmail('foulen2@esprit');
        $author->setUsername('foulen2');
        $entityManager->persist($author);
        $entityManager->flush();

        return $this->redirectToRoute('showAll');
    }
    #[Route("/delete/{id}", "delete")]
    public function delete(ManagerRegistry $doctrine, $id, AuthorRepository $repo)
    {

        $entityManager = $doctrine->getManager();
        $author = $repo->find($id);
        $entityManager->remove($author);
        $entityManager->flush();

        return $this->redirectToRoute('showAll');
    }


    #[Route('/showdetail/{id}', name: 'showdetail')]

    public function showById($id, AuthorRepository $repo)
    {
        $author = $repo->find($id);
        return $this->render('/author/showDetail.html.twig', ['author' => $author]);
    }
    #[Route('/addForm', name: 'addform')]
    public function addForm(Request $request, ManagerRegistry $doctrine)
    {
        $author = new Author();


        $form = $this->createForm(AuthorType::class, $author);


        $form->add('save', SubmitType::class, [
            'label' => 'Ajouter',
            'attr' => ['class' => 'btn btn-success mt-2']
        ]);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $doctrine->getManager();
            $em->persist($author);
            $em->flush();

            return $this->redirectToRoute('showAll');
        }


        return $this->render('author/add.html.twig', [
            'formulaire' => $form->createView(),
        ]);
    }
    #[Route('/edit/{id}', name: 'edit_author')]
    public function edit($id, Request $request, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $author = $em->getRepository(Author::class)->find($id);




        $form = $this->createForm(AuthorType::class, $author);


        $form->add('save', SubmitType::class, [
            'label' => 'Modifier',
            'attr' => ['class' => 'btn btn-primary mt-2']
        ]);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em->flush();
            return $this->redirectToRoute('showAll');
        }

        return $this->render('author/edit.html.twig', [
            'formulaire' => $form->createView(),
            'author' => $author,
        ]);
    }

    #[Route(path: '/ShowAllAuthorQR', name: 'ShowAllAuthorQR')]
    public function ShowAllAuthorQR(AuthorRepository $repo): Response
    {
        $authors = $repo->ShowAllAuthorQR();
        return $this->render('author/showAll.html.twig', [
            'authors' => $authors
        ]);
    }
}
