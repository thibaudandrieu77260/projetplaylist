<?php
namespace App\Controller;

use App\Entity\Playlist;
use App\Entity\Musique;
use App\Form\PlaylistType;
use App\Repository\PlaylistRepository;
use App\Repository\MusiqueRepository;
use App\Service\WikipediaService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class PlaylistController extends AbstractController
{
    private WikipediaService $wikipediaService;

    public function __construct(WikipediaService $wikipediaService)
    {
        $this->wikipediaService = $wikipediaService;
    }

    #[Route('/Playlist', name: 'Playlist')]
    public function index(PlaylistRepository $playlistRepository, MusiqueRepository $musiqueRepository): Response
    {
        $playlists = $playlistRepository->findAll();

        // Récupérer une musique aléatoire pour le quiz
        $musiques = $musiqueRepository->findAll();
        if (count($musiques) > 0) {
            $randomMusique = $musiques[array_rand($musiques)];
        } else {
            $randomMusique = null;
        }

        return $this->render('Playlist/index.html.twig', [
            'playlists' => $playlists,
            'quiz_musique' => $randomMusique,
        ]);
    }

    #[Route('/Playlist/new', name: 'Playlist_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $playlist = new Playlist();
        $form = $this->createForm(PlaylistType::class, $playlist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($playlist);
            $entityManager->flush();

            return $this->redirectToRoute('Playlist');
        }

        return $this->render('Playlist/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/Playlist/{id}', name: 'Playlist_show', methods: ['GET'])]
    public function show(Playlist $playlist): Response
    {
        return $this->render('Playlist/show.html.twig', [
            'playlist' => $playlist,
        ]);
    }

    #[Route('/Playlist/{id}/edit', name: 'Playlist_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Playlist $playlist, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PlaylistType::class, $playlist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Synchronize the music relation (if not using a bidirectional relationship)
            // $playlist->setMusiques($form->get('musiques')->getData());

            $entityManager->flush();

            return $this->redirectToRoute('Playlist');
        }

        return $this->render('Playlist/edit.html.twig', [
            'form' => $form->createView(),
            'playlist' => $playlist,
        ]);
    }

    #[Route('/Playlist/{id}/delete', name: 'Playlist_delete', methods: ['POST'])]
    public function delete(Request $request, Playlist $playlist, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$playlist->getId(), $request->request->get('_token'))) {
            $entityManager->remove($playlist);
            $entityManager->flush();
        }

        return $this->redirectToRoute('Playlist');
    }

    #[Route('/Playlist/quiz', name: 'Playlist_quiz', methods: ['POST'])]
    public function quiz(Request $request, MusiqueRepository $musiqueRepository): Response
    {
        $musiqueId = $request->request->get('musique_id');
        $userAnswer = $request->request->get('artist');

        // Récupérer la musique à partir de l'ID
        $musique = $musiqueRepository->find($musiqueId);

        if ($musique && strtolower($userAnswer) === strtolower($musique->getArtiste())) {
            $biography = $this->wikipediaService->getBiography($musique->getArtiste());
            $result = 'Bonne réponse !';
        } else {
            $biography = null;
            $result = 'Mauvaise réponse ! L\'artiste correct était ' . $musique->getArtiste();
        }

        return $this->render('Playlist/quiz_result.html.twig', [
            'result' => $result,
            'musique' => $musique,
            'biography' => $biography,
        ]);
    }
}