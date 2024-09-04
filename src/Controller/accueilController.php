<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class accueilController extends AbstractController
{
    #[Route('/', name: 'root')]
    public function root(): Response
    {
        return $this->redirectToRoute('accueil');
    }
    
    #[Route('/accueil', name: 'accueil')]
    public function index(): Response
    {
        $user = $this->getUser();

        return $this->render('accueil/accueil.html.twig', [
            'user' => $user,
        ]);

        // Vérifier si l'utilisateur est connecté et s'il a le rôle d'administrateur
        $isAdmin = $this->isGranted('ROLE_ADMIN');

        return $this->render('base.html.twig', [
            'isAdmin' => $isAdmin,
        ]);
    }
}
