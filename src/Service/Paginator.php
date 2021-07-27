<?php

namespace App\Service;

use Doctrine\ORM\Query;

class Paginator implements \IteratorAggregate, \Countable
{
    private int $itemsTotal;
    private int $page;
    private float $pagesTotal;
    private int $offset;
    private int $limit;
    private array $items = [];

    /**
     * Hydrates properties of the class.
     */
    public function paginate(Query $query, int $page = 1, int $limit = 10): self
    {
        if ($limit <= 0 || $page <= 0) {
            throw new \LogicException(
                "Invalid item per page number. Limit: $limit and Page: $page, must be positive non-zero integers"
            );
        }

        $this->page = $page;
        $this->limit = $limit;
        $this->offset = ($page - 1) * $limit;

        $this->setPagesTotal($query);
        $this->executeQuery($query);

        return $this;
    }

    /**
     * Executes the query.
     */
    public function executeQuery(Query $query): void
    {
        $this->items = $query
            ->setFirstResult($this->offset)
            ->setMaxResults($this->limit)
            ->getResult();
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getPagesTotal(): float
    {
        return $this->pagesTotal;
    }

    public function setPagesTotal(Query $query): void
    {
        $this->itemsTotal = count($query->getScalarResult());
        $this->pagesTotal = ceil($this->itemsTotal / $this->limit);
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getItemsTotal(): int
    {
        return $this->itemsTotal;
    }

    public function count(): int
    {
        return count($this->items);
    }

    /**
     * Returns an iterator for items.
     *
     * @return \ArrayIterator An \ArrayIterator instance
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }
}
