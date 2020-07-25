<?php
declare(strict_types=1);

namespace App\Swarrot\Message;

use App\Models\Song;
use Swarrot\Broker\Message;

/**
 * Class SongCreationMessage
 * @package App\Swarrot\Message
 */
class SongCreationMessage extends Message
{
    public const TYPE_SONG_CREATION = 'song_creation';

    /**
     * SongCreationMessage constructor.
     * @param string $name
     * @param string $singer
     * @param int $year
     * @param int $duration
     */
    public function __construct(
        string $name,
        string $singer,
        int $year,
        int $duration
    )
    {
        $data = [
            'name'     => $name,
            'singer'   => $singer,
            'year'     => $year,
            'duration' => $duration,

        ];

        $body = json_encode($data);

        parent::__construct($body);
    }


    /**
     * @param Song $song
     * @return static
     */
    public static function create(Song $song): self
    {
        return new self(
            $song->getName(),
            $song->getSinger(),
            $song->getYear(),
            $song->getDuration(),
        );
    }

}