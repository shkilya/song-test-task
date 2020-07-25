<?php
declare(strict_types=1);

namespace App\Swarrot\Message;

/**
 * Class SongCreationMessageInput
 * @package App\Swarrot\Message
 */
final class SongCreationMessageInput
{

    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private string $singer;

    /**
     * @var int
     */
    private int $year;

    /**
     * @var int
     */
    private int $duration;

    /**
     * SongCreationMessageInput constructor.
     */
    private function __construct()
    {
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