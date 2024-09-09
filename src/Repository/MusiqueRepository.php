<?php

namespace App\Repository;

use App\Entity\Musique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Musique>
 */
class MusiqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Musique::class);
    }

    public function save(Musique $musique, bool $flush = false): void
    {
        $this->_em->persist($musique);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(Musique $musique, bool $flush = false): void
    {
        $this->_em->remove($musique);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
