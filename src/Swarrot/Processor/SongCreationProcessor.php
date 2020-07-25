<?php
declare(strict_types=1);

namespace App\Swarrot\Processor;

use App\Entity\Song;
use App\Swarrot\Message\SongCreationMessageInput;
use App\Utils\SongManager;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;
use Swarrot\Broker\Message;
use Swarrot\Processor\ProcessorInterface;

/**
 * Class SongCreationProcessor
 * @package App\Swarrot\Processor
 */
class SongCreationProcessor implements  ProcessorInterface
{
    /**
     * @var SongManager
     */
    private SongManager $songManager;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * SongCreationProcessor constructor.
     * @param SongManager $songManager
     * @param EntityManagerInterface $entityManager
     * @param SerializerInterface $serializer
     * @param LoggerInterface $logger
     */
    public function __construct(
        SongManager $songManager,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        LoggerInterface $logger
    )
    {
        $this->songManager = $songManager;
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->logger = $logger;
    }

    /**
     * @param Message $message
     * @param array $options
     * @return bool
     */
    public function process(Message $message, array $options): bool
    {
        try {
            /** @var SongCreationMessageInput $songCreationMessage */
            $songCreationMessage = $this->serializer->deserialize(
                $message->getBody(),
                SongCreationMessageInput::class,
                'json'
            );

            $song = (new Song())
                ->setName($songCreationMessage->getName())
                ->setSinger($songCreationMessage->getSinger())
                ->setYear($songCreationMessage->getYear())
                ->setDuration($songCreationMessage->getDuration());

            $this->songManager->createSong($song);

        }catch (\Exception $exception){
            $this->logger->critical('Song creation processor error',[
                'message'=>$exception->getMessage()
            ]);
            throw $exception;
        }
    }
}