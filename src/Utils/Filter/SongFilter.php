<?php
declare(strict_types=1);

namespace App\Utils\Filter;

/**
 * Class SongFilter
 * @package App\Utils\Filter
 */
class SongFilter
{
    /**
     * @var int
     */
    private int    $page = 1;

    /**
     * @var int
     */
    private int    $limit;

    /**
     * @var string
     */
    private string $sortField;


    /**
     * @var string
     */
    private string $sortOrder;

    /**
     * SongFilter constructor.
     * @param int $page
     * @param int $limit
     * @param string $sortField
     * @param string $sortOrder
     */
    public function __construct(int $page, int $limit, string $sortField, string $sortOrder)
    {

        if ($page < 1) {
            throw new \DomainException('Page should be more or eq 1');
        }
        $this->page = $page;

        $this->limit     = $limit;
        $this->sortField = $sortField;
        $this->sortOrder = $sortOrder;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return string
     */
    public function getSortField(): string
    {
        return $this->sortField;
    }

    /**
     * @return string
     */
    public function getSortOrder(): string
    {
        return $this->sortOrder;
    }
}