<?php

namespace App\HAL;

class HalRessource
{
    /**
     * @var Link[]
     */
    private $links = [];

    /**
     * The ressource id.
     */
    private int $id;

    /**
     * The ressource type.
     */
    private string $type;

    /**
     * The ressource.
     */
    private object $ressource;

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

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getRessource(): object
    {
        return $this->ressource;
    }

    public function setRessource(object $ressource): self
    {
        $this->ressource = $ressource;

        return $this;
    }
}
