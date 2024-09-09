<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/admin')]
##[IsGranted('ROLE_ADMIN')]
class adminController extends AbstractController
{

    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }


            /*------------------------------     USER    -------------------------------------------------------*/

    #[Route('/gestion_utilisateurs', name: 'gestion_utilisateurs')]
    public function gestionUtilisateurs(EntityManagerInterface $entityManager): Response
    {
        $utilisateurs = $entityManager->getRepository(User::class)->findAll();

        return $this->render('admin/gestion_utilisateurs.html.twig', [
            'utilisateurs' => $utilisateurs,
        ]);
    }

    #[Route('/ajouter_utilisateur', name: 'ajouter_utilisateur')]
    public function ajouterUtilisateur(Request $request, EntityManagerInterface $entityManager): Response
    {
        $utilisateur = new User();
        $form = $this->createForm(UserType::class, $utilisateur, ['is_edit' => false]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $this->passwordHasher->hashPassword(
                $utilisateur,
                $form->get('password')->getData()
            );
            $utilisateur->setPassword($hashedPassword);

            $entityManager->persist($utilisateur);
            $entityManager->flush();

            return $this->redirectToRoute('gestion_utilisateurs');
        }

        return $this->render('admin/ajouter_utilisateur.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/modifier_utilisateur/{id}', name: 'modifier_utilisateur')]
    public function modifierUtilisateur(Request $request, User $utilisateur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $utilisateur, ['is_edit' => true]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $form->get('password')->getData();
            if (!empty($newPassword)) {
                $hashedPassword = $this->passwordHasher->hashPassword(
                    $utilisateur,
                    $newPassword
                );
                $utilisateur->setPassword($hashedPassword);
            }

            $entityManager->flush();

            return $this->redirectToRoute('gestion_utilisateurs');
        }

        return $this->render('admin/modifier_utilisateur.html.twig', [
            'form' => $form->createView(),
            'utilisateur' => $utilisateur,
        ]);
    }

    #[Route('/supprimer_utilisateur/{id}', name: 'supprimer_utilisateur')]
    public function supprimerUtilisateur(User $utilisateur, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($utilisateur);
        $entityManager->flush();

        return $this->redirectToRoute('gestion_utilisateurs');
    }
}

