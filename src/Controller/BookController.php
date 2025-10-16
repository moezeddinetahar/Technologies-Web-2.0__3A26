<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Book;
use App\Repository\BookRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


use App\Form\BookType;
use App\Form\RechercheBookType;
use App\Repository\AuthorRepository;

final class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/book/add', name: 'book_add')]
    public function addBook(Request $request, ManagerRegistry $doctrine): Response
    {
        $book = new Book();
        $book->setPublished(true);

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $doctrine->getManager();


            $author = $book->getAuthor();
            $author->setNbBooks($author->getNbBooks() + 1);

            $em->persist($book);
            $em->flush();

            return $this->redirectToRoute('app_books_published');
        }

        return $this->render('book/addbook.html.twig', [
            'formBook' => $form->createView(),
        ]);
    }

    #[Route(path: '/listAuthorByEmail', name: 'listAuthorByEmail')]
    public function listAuthorByEmail(AuthorRepository $repo): Response
    {
        $authors = $repo->listAuthorByEmail();
        return $this->render('author/showAll.html.twig', [
            'authors' => $authors
        ]);
    }

    #[Route('/book/published', name: 'app_books_published')]
    public function listPublishedBooks(Request $request, BookRepository $repo): Response
    {
        $form = $this->createForm(RechercheBookType::class, null, [
            'method' => 'GET',
        ]);
        $form->handleRequest($request);

        $books = $repo->findBy(['published' => true]);

        if ($form->isSubmitted())
        {
            $data = $form->getData();
            if (!empty($data['id']))
            {
                $books = $repo->searchBookByid($data['id']);
            }
        }

        $allBooks = $repo->findAll();
        $publishedCount = count($books);
        $unpublishedCount = count($allBooks) - $publishedCount;

        return $this->render('book/show.html.twig', [
            'books' => $books,
            'publishedCount' => $publishedCount,
            'unpublishedCount' => $unpublishedCount,
            'totalCount' => count($allBooks),
            'RechercheBookType' => $form->createView(),
        ]);
    }



    #[Route('/book/edit/{id}', name: 'app_book_edit')]
    public function edit(int $id, Request $request, ManagerRegistry $doctrine, BookRepository $repo): Response
    {

        $book = $repo->find($id);




        $form = $this->createForm(BookType::class, $book);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $doctrine->getManager();
            $em->flush();


            return $this->redirectToRoute('app_books_published');
        }

        return $this->render('book/edit.html.twig', [
            'formulaire' => $form->createView(),
            'book' => $book,
        ]);
    }
    #[Route('/book/{id}', name: 'app_book_show')]
    public function show(int $id, BookRepository $repo): Response
    {

        $book = $repo->find($id);


        return $this->render('book/showdetail.html.twig', [
            'book' => $book,
        ]);
    }
    #[Route('/book/delete/{id}', name: 'app_book_delete')]
    public function delete(int $id, ManagerRegistry $doctrine, BookRepository $repo): Response
    {

        $book = $repo->find($id);


        $em = $doctrine->getManager();
        $em->remove($book);
        $em->flush();



        return $this->redirectToRoute('app_books_published');
    }
}
