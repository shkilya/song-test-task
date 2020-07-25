<?php

namespace App\Repository;

use App\Entity\Song;
use App\Utils\Filter\SongFilter;
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
     * @param SongFilter $filter
     *
     * @return Song[]|null
     */
    public function getAll(
        SongFilter $filter
    ): ?array {
        $offset = ($filter->getPage() - 1) * $filter->getLimit();
        $maxResults = $filter->getLimit();

        $queryBuilder = $this->createQueryBuilder('s')
            ->orderBy( 's.'.$filter->getSortField(), $filter->getSortOrder());

        $queryBuilder = $queryBuilder->getQuery();

        if (!is_null($offset)) {
            $queryBuilder->setFirstResult($offset);
        }

        if (!is_null($maxResults)) {
            $queryBuilder->setMaxResults($maxResults);
        }

        return $queryBuilder->getResult();
    }
}
