<?php
// src/Controller/ConfigurationController.php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class configurationController extends AbstractController
{
    #[Route('/configuration', name: 'configuration')]
    #[IsGranted('ROLE_USER')]
    public function configureAccount(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        
        // Créez un formulaire basé sur UserType, mais avec les options pour édition
        $form = $this->createForm(UserType::class, $user, ['is_edit' => true]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Hash the new password if it has been changed
            $newPassword = $form->get('password')->getData();
            if ($newPassword) {
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $newPassword
                );
                $user->setPassword($hashedPassword);
            }

            $entityManager->flush();

            $this->addFlash('success', 'Votre compte a été mis à jour avec succès.');

            return $this->redirectToRoute('configuration');
        }

        return $this->render('configuration/configuration.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
