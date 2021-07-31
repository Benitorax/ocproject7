<?php

namespace App\HAL;

use App\HAL\Link;

class HalPaginator
{
    /**
     * @var Link[]
     */
    private $links = [];

    /**
     * Number of items in this page.
     */
    private int $count;

    /**
     * Total number of items.
     */
    private int $total;

    /**
     * The items.
     */
    private array $embedded = [];

    /**
     * @return Link[]
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * @param Link[] $links
     */
    public function setLinks($links): self
    {
        $this->links = $links;

        return $this;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function setCount(int $count): self
    {
        $this->count = $count;

        return $this;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getEmbedded(): array
    {
        return $this->embedded;
    }

    public function setEmbedded(array $embedded): self
    {
        $this->embedded = $embedded;

        return $this;
    }
}
