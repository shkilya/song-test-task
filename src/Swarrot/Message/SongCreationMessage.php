<?php
declare(strict_types=1);

namespace App\Swarrot\Message;

use Swarrot\Broker\Message;

class SongCreationMessage extends Message
{
    public function __construct(
        string $name,
        string $singer,
        string $year,
        string $duration
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

}