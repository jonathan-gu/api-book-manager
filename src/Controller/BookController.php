<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use PhpParser\Node\Expr\Cast\Int_;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/books')]
class BookController extends AbstractController
{
    #[Route('', name: 'app_book_get_all', methods:['GET'])]
    public function get_all(BookRepository $bookRepository): JsonResponse
    {
        $books = $bookRepository->findAll();
        $booksArray = [];
        foreach ($books as $key => $book) {
            $booksArray[$key]["id"] = $book->getId();
            $booksArray[$key]["title"] = $book->getTitle();
            $booksArray[$key]["author"] = $book->getAuthor();
            $booksArray[$key]["publication_year"] = $book->getPublicationYear();
        }
        return new JsonResponse([
            "succes" => true,
            "data" => [
                "books" => $booksArray
            ]
        ], 200);
    }

    #[Route('/{id}', name: 'app_book_get_by_id', methods:['GET'])]
    public function get_by_id($id, BookRepository $bookRepository): JsonResponse
    {
        if (!filter_var($id, FILTER_VALIDATE_INT)) {
            return new JsonResponse([
                "succes" => false,
                "error" => "Bad Request"
            ], 400);
        }
        $book = $bookRepository->findOneById($id);
        if ($book == null) {
            return new JsonResponse([
                "succes" => false,
                "error" => "The book was not found"
            ], 404);
        }
        return new JsonResponse([
            "succes" => true,
            "data" => [
                "book" => [
                    "id" => $book->getId(),
                    "title" => $book->getTitle(),
                    "author" => $book->getAuthor(),
                    "publication_year" => $book->getPublicationYear()
                ]
            ]
        ], 200);
    }

    #[Route('', name: 'app_book_add', methods:['POST'])]
    public function add(Request $request, BookRepository $bookRepository): JsonResponse
    {
        $request_decode = json_decode($request->getContent());
        if (is_string($request_decode->title) && strlen($request_decode->title) < 1 && strlen($request_decode->title) < 255 && is_string($request_decode->author) && strlen($request_decode->author) < 1 && strlen($request_decode->author) < 255 && is_int($request_decode->publication_year) && $request_decode->publication_year > 0 && $request_decode->publication_year <= date("Y")) {
            $book = new Book();
            $book->setTitle($request_decode->title);
            $book->setAuthor($request_decode->author);
            $book->setPublicationYear($request_decode->publication_year);
            $bookRepository->save($book, true);
            $lastBook = $bookRepository->findLastBook();
            return new JsonResponse([
                "succes" => true,
                "data" => [
                    "book" => [
                        "id" => $lastBook->getId(),
                        "title" => $lastBook->getTitle(),
                        "author" => $lastBook->getAuthor(),
                        "publication_year" => $lastBook->getPublicationYear()
                    ]
                ]
            ], 201);
        }
        return new JsonResponse([
            "succes" => false,
            "error" => "Bad Request"
        ], 400);
    }

    #[Route('/{id}', name: 'app_book_update', methods:['PUT'])]
    public function update($id, Request $request, BookRepository $bookRepository): JsonResponse
    {
        $request_decode = json_decode($request->getContent());
        if (!filter_var($id, FILTER_VALIDATE_INT) || (empty($request_decode->title) && empty($request_decode->author) && empty($request_decode->publication_year))) {
            return new JsonResponse([
                "succes" => false,
                "error" => "Bad Request"
            ], 400);
        }
        if (isset($request_decode->title)) {
            if (!is_string($request_decode->title) || strlen($request_decode->title) < 1 || strlen($request_decode->title) > 255) {
                return new JsonResponse([
                    "succes" => false,
                    "error" => "Bad Request"
                ], 400);
            }
        }
        if (isset($request_decode->author)) {
            if (!is_string($request_decode->author) || strlen($request_decode->author) < 1 || strlen($request_decode->author) > 255) {
                return new JsonResponse([
                    "succes" => false,
                    "error" => "Bad Request"
                ], 400);
            }
        }
        if (isset($request_decode->publication_year)) {
            if (!is_int($request_decode->publication_year) || $request_decode->publication_year < 1 || $request_decode->publication_year > intval(date("Y"))) {
                return new JsonResponse([
                    "succes" => false,
                    "error" => "Bad Request"
                ], 400);
            }
        }
        $book = $bookRepository->findOneById($id);
        if ($book == null) {
            return new JsonResponse([
                "succes" => false,
                "error" => "The book was not found"
            ], 404);
        }
        if (isset($request_decode->title)) {
            $book->setTitle($request_decode->title);
        }
        if (isset($request_decode->author)) {
            $book->setAuthor($request_decode->author);
        }
        if (isset($request_decode->publication_year)) {
            $book->setPublicationYear($request_decode->publication_year);
        }
        $bookRepository->save($book, true);
        return new JsonResponse([
            "succes" => true,
            "data" => [
                "book" => [
                    "id" => $book->getId(),
                    "title" => $book->getTitle(),
                    "author" => $book->getAuthor(),
                    "publication_year" => $book->getPublicationYear()
                ]
            ]
        ], 200);
    }

    #[Route('/{id}', name: 'app_book_delete', methods:['DELETE'])]
    public function delete($id, BookRepository $bookRepository): JsonResponse
    {
        if (!filter_var($id, FILTER_VALIDATE_INT)) {
            return new JsonResponse([
                "succes" => false,
                "error" => "Bad Request"
            ], 400);
        }
        $book = $bookRepository->findOneById($id);
        if ($book == null) {
            return new JsonResponse([
                "succes" => false,
                "error" => "The book was not found"
            ], 404);
        }
        $bookRepository->remove($book, true);
        return new JsonResponse([
            "succes" => true,
            "data" => [
                "book" => [
                    "id" => $book->getId(),
                    "title" => $book->getTitle(),
                    "author" => $book->getAuthor(),
                    "publication_year" => $book->getPublicationYear()
                ]
            ]
        ], 200);
    }
}
