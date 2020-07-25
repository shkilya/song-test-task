<?php

namespace App\Repository;

use App\Entity\Song;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Song|null find($id, $lockMode = null, $lockVersion = null)
 * @method Song|null findOneBy(array $criteria, array $orderBy = null)
 * @method Song[]    findAll()
 * @method Song[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SongRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Song::class);
    }


    /**
     * @param int $page
     * @param int $limit
     * @return Song[]|null
     */
    public function getAll(int $page = 1 ,int $limit = Song::DEFAULT_LIMIT)
    {
        $offset = ($page-1)*$limit;
        $maxResults = $limit;

        $queryBuilder = $this->createQueryBuilder('s')
            ->getQuery();

        if (!is_null($offset)) {
            $queryBuilder->setFirstResult($offset);
        }

        if (!is_null($maxResults)) {
            $queryBuilder->setMaxResults($maxResults);
        }

        return $queryBuilder->getResult();
    }
}
