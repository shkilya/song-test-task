<?php
declare(strict_types=1);

namespace App\Utils;

use App\Entity\Song;
use Doctrine\ORM\EntityManagerInterface;

class SongManager
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * SongManager constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Song $song
     * @return Song|null
     */
    public function createSong(Song $song): Song
    {
        $this->entityManager->persist($song);
        $this->entityManager->flush();
        return  $song;
    }

    public function updateSong(Song $song): Song
    {
        $this->entityManager->flush();
        return  $song;
    }


}