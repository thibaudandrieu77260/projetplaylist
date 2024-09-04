<?php


namespace App\Controller;

use App\Entity\Playlist;
use App\Form\PlaylistType;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class PrincipaleController extends AbstractController
{
    #[Route('/principale', name: 'principale')]
    public function index(PlaylistRepository $playlistRepository): Response
    {
        $playlists = $playlistRepository->findAll();

        return $this->render('principale/index.html.twig', [
            'playlists' => $playlists,
        ]);
    }

    #[Route('/principale/new', name: 'principale_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $playlist = new Playlist();
        $form = $this->createForm(PlaylistType::class, $playlist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($playlist);
            $entityManager->flush();

            return $this->redirectToRoute('principale');
        }

        return $this->render('principale/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/principale/{id}', name: 'principale_show', methods: ['GET'])]
    public function show(Playlist $playlist): Response
    {
        return $this->render('principale/show.html.twig', [
            'playlist' => $playlist,
        ]);
    }

    #[Route('/principale/{id}/edit', name: 'principale_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Playlist $playlist, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PlaylistType::class, $playlist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('principale');
        }

        return $this->render('principale/edit.html.twig', [
            'form' => $form->createView(),
            'playlist' => $playlist,
        ]);
    }

    #[Route('/principale/{id}/delete', name: 'principale_delete', methods: ['POST'])]
    public function delete(Request $request, Playlist $playlist, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$playlist->getId(), $request->request->get('_token'))) {
            $entityManager->remove($playlist);
            $entityManager->flush();
        }

        return $this->redirectToRoute('principale');
    }
}
       
        

      

          

       

    
        

       

     


