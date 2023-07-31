<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/books')]
class BookController extends AbstractController
{
    #[Route('/', name: 'app_book_add', methods:['POST'])]
    public function add(Request $request, BookRepository $bookRepository): JsonResponse
    {
        $request_decode = json_decode($request->getContent());
        if (is_string($request_decode->title) && strlen($request_decode->title) > 0 && strlen($request_decode->title) < 255 && is_string($request_decode->author) && strlen($request_decode->author) > 0 && strlen($request_decode->author) < 255 && is_int($request_decode->publication_year) && $request_decode->publication_year > 0 && $request_decode->publication_year <= date("Y")) {
            $book = new Book();
            $book->setTitle($request_decode->title);
            $book->setAuthor($request_decode->author);
            $book->setPublicationYear($request_decode->publication_year);
            $bookRepository->save($book, true);
            $LastBook = $bookRepository->findLastBook();
            return new JsonResponse([
                "succes" => true,
                "data" => [
                    "book" => [
                        "id" => $LastBook->getId(),
                        "title" => $LastBook->getTitle(),
                        "author" => $LastBook->getAuthor(),
                        "pulication_year" => $LastBook->getPublicationYear()
                    ]
                ]
            ], 201);
        }
        return new JsonResponse([
            "succes" => false,
            "error" => "Bad Request"
        ], 400);
    }
}
