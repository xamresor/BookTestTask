<?php
namespace App\Controller;

use App\Entity\User;
use App\Repository\AuthorRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    #[Route('/authors', name: 'app_authors_page')]
    public function makeTable(ManagerRegistry $doctrine, UserRepository $userRepository): Response
    {
        $data = $userRepository->findAllAuthors();
        return $this->render('authors/index.html.twig', [
            'names' => array_keys(reset($data)),
            'data' => $data
        ]);
    }
}