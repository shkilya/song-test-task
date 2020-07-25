<?php

namespace App\Entity;

use App\Repository\SongRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SongRepository::class)
 */
class Song
{
    public const DEFAULT_LIMIT = 20;

    public const DEFAULT_SORT_ORDER = "ASC";
    public const DEFAULT_SORT_FIELD = "id";

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     *
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private string $singer;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     */
    private int $year;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     */
    private int $duration;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSinger(): ?string
    {
        return $this->singer;
    }

    public function setSinger(string $singer): self
    {
        $this->singer = $singer;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }
}
