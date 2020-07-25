<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Song;
use App\Repository\SongRepository;
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
    private const PARAM_PAGE  = 'page';
    private const PARAM_SORT  = 'sort';


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
     * SongController constructor.
     * @param SongRepository $songRepository
     * @param EntityManagerInterface $entityManager
     * @param SongManager $songManager
     * @param LoggerInterface $logger
     * @param ValidatorInterface $validator
     */
    public function __construct(
        SongRepository $songRepository,
        EntityManagerInterface $entityManager,
        SongManager $songManager,
        LoggerInterface $logger,
        ValidatorInterface $validator
    )
    {
        $this->songRepository = $songRepository;
        $this->entityManager  = $entityManager;
        $this->songManager    = $songManager;
        $this->logger         = $logger;
        $this->validator = $validator;
    }


    /**
     * @Route("/songs", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function getList(Request $request): Response
    {
        $page = (int)$request->get(self::PARAM_PAGE, 1);

        $limit = (int)$request->get(self::PARAM_LIMIT, Song::DEFAULT_LIMIT);

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
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {

        try {
            $song = (new Song())
                ->setName((string)$request->get('name'))
                ->setSinger((string)$request->get('singer'))
                ->setDuration((int)$request->get('duration'))
                ->setYear((int)$request->get('year'));

            $errors = $this->validator->validate($song);

            if(count($errors)){
                throw new ValidatorException('Song validation error',['message'=>$errors]);
            }

            $this->songManager->createSong($song);
            return $this->json(['message' => sprintf('Song  %s was created', $song->getName())], 201);
        } catch (\Exception $exception) {
            $this->logger->critical('Song create exception', ['message' => $exception->getMessage()]);
            return $this->json(['Something went wrong'], 500);
        }
    }

    /**
     * @Route("/songs/{id}", methods={"PUT"})
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, int $id): Response
    {
        try {

            $song = $this->songRepository->find($id);
            $song
                ->setName((string)$request->get('name'))
                ->setSinger((string)$request->get('singer'))
                ->setDuration((int)$request->get('duration'))
                ->setYear((int)$request->get('year'));
            $errors = $this->validator->validate($song);

            if(count($errors)){
                throw new ValidatorException('Song validation error',['message'=>$errors]);
            }


            $this->entityManager->flush();

            return $this->json(['message' => sprintf('Song  %s was created', $song->getName())]);
        } catch (\Exception $exception) {
            $this->logger->critical('Song update exception', ['message' => $exception->getMessage()]);
            return $this->json(['Something went wrong'], 500);
        }
    }

    /**
     * @Route("/songs/{id}", methods={"DELETE"})
     * @param int $id
     * @return Response
     */
    public function delete(int $id): Response
    {
        try {
            $song = $this->songRepository->find($id);
            $this->entityManager->remove($song);
            $this->entityManager->flush();
            return $this->json(['message' => sprintf('Song %s was removed', $song->getName())]);
        } catch (\Exception $exception) {
            $this->logger->critical('Song create exception', ['message' => $exception->getMessage()]);
            return $this->json(['message' => 'Something went wrong'], 500);
        }
    }
}