<?php
namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InviterController extends AbstractController
{
    #[Route('/inviters', name: 'app_inviters_page')]
    public function makeTable(ManagerRegistry $doctrine, UserRepository $userRepository): Response
    {
        $data = $userRepository->findAllInviters();
        return $this->render('inviters/index.html.twig', [
            'names' => array_keys(reset($data)),
            'data' => $data
        ]);
    }
}