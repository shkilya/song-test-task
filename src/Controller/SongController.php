<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Song;
use App\Repository\SongRepository;
use App\Swarrot\Publisher\SongCreationPublisher;
use App\Utils\Filter\SongFilter;
use App\Utils\SongManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SongController extends AbstractController
{
    private const PARAM_LIMIT = 'limit';
    private const PARAM_PAGE = 'page';
    private const PARAM_SORT = 'sort';

    /**
     * @var SongRepository
     */
    private SongRepository $songRepository;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var SongManager
     */
    private SongManager $songManager;
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;
    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;
    /**
     * @var SongCreationPublisher
     */
    private SongCreationPublisher $songCreationPublisher;

    /**
     * SongController constructor.
     *
     * @param SongRepository         $songRepository
     * @param EntityManagerInterface $entityManager
     * @param SongCreationPublisher  $songCreationPublisher
     * @param SongManager            $songManager
     * @param LoggerInterface        $logger
     * @param ValidatorInterface     $validator
     */
    public function __construct(
        SongRepository $songRepository,
        EntityManagerInterface $entityManager,
        SongCreationPublisher $songCreationPublisher,
        SongManager $songManager,
        LoggerInterface $logger,
        ValidatorInterface $validator
    ) {
        $this->songRepository = $songRepository;
        $this->entityManager = $entityManager;
        $this->songManager = $songManager;
        $this->logger = $logger;
        $this->validator = $validator;
        $this->songCreationPublisher = $songCreationPublisher;
    }

    /**
     * @Route("/songs", methods={"GET"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function getList(Request $request): Response
    {
        $page = (int) $request->get(self::PARAM_PAGE, 1);

        $limit = (int) $request->get(self::PARAM_LIMIT, Song::DEFAULT_LIMIT);

        $sortOrder = $request->get(self::PARAM_SORT, Song::DEFAULT_SORT_ORDER);

        $sortField = $request->get(self::PARAM_SORT, Song::DEFAULT_SORT_FIELD);

        $filter = new SongFilter(
            $page,
            $limit,
            $sortField,
            $sortOrder,
        );

        return $this->json($this->songRepository->getAll($filter));
    }

    /**
     * @param int $id
     * @Route("/songs/{id}", methods={"GET"})
     *
     * @return Response
     */
    public function getOne(int $id): Response
    {
        return $this->json(
            $this->songRepository->find($id)
        );
    }

    /**
     * @Route("/songs", methods={"POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function create(Request $request): Response
    {
        try {
            $song = new \App\Models\Song(
                (string) $request->get('name'),
                (string) $request->get('singer'),
                (int) $request->get('duration'),
                (int) $request->get('year')
            );

            $errors = $this->validator->validate($song);

            if (count($errors)) {
                throw new ValidatorException('Song validation error', ['message' => $errors]);
            }

            /*
             *  Publish message in queue
             */
            $this->songCreationPublisher->publicSongCreationMessage($song);

            return $this->json(['message' => sprintf('Song  %s was created', $song->getName())], 201);
        } catch (\Exception $exception) {
            $this->logger->critical('Song create exception', ['message' => $exception->getMessage()]);

            throw new \Exception('Song create error');
        }
    }

    /**
     * @Route("/songs/{id}", methods={"PUT"})
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function update(Request $request, int $id): Response
    {
        try {
            $song = $this->songRepository->find($id);
            $song
                ->setName((string) $request->get('name'))
                ->setSinger((string) $request->get('singer'))
                ->setDuration((int) $request->get('duration'))
                ->setYear((int) $request->get('year'));

            $errors = $this->validator->validate($song);

            if (count($errors)) {
                throw new ValidatorException('Song validation error', ['message' => $errors]);
            }

            $this->entityManager->flush();

            return $this->json(['message' => sprintf('Song  %s was created', $song->getName())]);
        } catch (\Exception $exception) {
            $this->logger->critical('Song update exception', ['message' => $exception->getMessage()]);

            throw new \Exception('Song update error');
        }
    }

    /**
     * @Route("/songs/{id}", methods={"DELETE"})
     *
     * @param int $id
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function delete(int $id): Response
    {
        try {
            $song = $this->songRepository->find($id);
            $this->entityManager->remove($song);
            $this->entityManager->flush();

            return $this->json(['message' => sprintf('Song %s was removed', $song->getName())]);
        } catch (\Exception $exception) {
            $this->logger->critical('Song delete exception', ['message' => $exception->getMessage()]);

            throw new \Exception('Song delete error');
        }
    }
}