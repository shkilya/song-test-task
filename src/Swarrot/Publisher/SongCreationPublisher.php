<?php
declare(strict_types=1);

namespace App\Swarrot\Publisher;

use App\Models\Song;
use App\Swarrot\Message\SongCreationMessage;
use Swarrot\SwarrotBundle\Broker\Publisher;

/**
 * Class SongCreationPublisher
 * @package App\Swarrot\Publisher
 */
class SongCreationPublisher
{
    /**
     * @var Publisher
     */
    private Publisher $publisher;

    /**
     * SongCreationPublisher constructor.
     * @param Publisher $publisher
     */
    public function __construct(
        Publisher $publisher
    )
    {
        $this->publisher = $publisher;
    }


    /**
     * @param Song $song
     */
    public function publicSongCreationMessage(Song $song)
    {
        $this->publisher->publish(
            SongCreationMessage::TYPE_SONG_CREATION,
            SongCreationMessage::create($song)
        );
    }
}