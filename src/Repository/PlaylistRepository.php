<?php

namespace App\Repository;

use App\Entity\Playlist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Playlist>
 */
class PlaylistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Playlist::class);
    }

    public function findOneRandom(): ?Playlist
    {
        // Récupérer le nombre total de Playlist dans la base de données
        $count = $this->createQueryBuilder('b')
            ->select('COUNT(b.id)')
            ->getQuery()
            ->getSingleScalarResult();

        if ($count > 0) {
            // Générer un nombre aléatoire entre 0 et le nombre total de Playlist - 1
            $randomOffset = rand(0, $count - 1);

            // Récupérer une playlist à l'index aléatoire
            return $this->createQueryBuilder('b')
                ->setMaxResults(1)
                ->setFirstResult($randomOffset)
                ->getQuery()
                ->getOneOrNullResult();
        }

        // Aucune Playlist trouvée dans la base de données
        return null;
    }
}