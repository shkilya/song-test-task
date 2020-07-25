<?php
declare(strict_types=1);

namespace App\Swarrot\Publisher;

use Swarrot\SwarrotBundle\Broker\Publisher;

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


    public function publicSongCreationMessage(Song $song)
    {

    }

}