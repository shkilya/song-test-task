<?php

declare(strict_types=1);

namespace App\Models;

use Symfony\Component\Validator\Constraints as Assert;

final class Song
{
    /**
     * @Assert\NotBlank
     */
    private string $name;

    /**
     * @Assert\NotBlank
     */
    private string $singer;

    /**
     * @Assert\NotBlank
     */
    private int $year;

    /**
     * @Assert\NotBlank
     */
    private int $duration;

    /**
     * Song constructor.
     *
     * @param string $name
     * @param string $singer
     * @param int    $year
     * @param int    $duration
     */
    public function __construct(string $name, string $singer, int $year, int $duration)
    {
        $this->name = $name;
        $this->singer = $singer;
        $this->year = $year;
        $this->duration = $duration;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSinger(): string
    {
        return $this->singer;
    }

    /**
     * @return int
     */
    public function getYear(): int
    {
        return $this->year;
    }

    /**
     * @return int
     */
    public function getDuration(): int
    {
        return $this->duration;
    }
}