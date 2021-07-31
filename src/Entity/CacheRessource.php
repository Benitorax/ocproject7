<?php

namespace App\Entity;

use App\Repository\CacheRessourceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CacheRessourceRepository::class)
 */
class CacheRessource
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $entityClass;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $entityId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $page;

    /**
     * @ORM\Column(type="text")
     */
    private string $content = '';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEntityClass(): ?string
    {
        return $this->entityClass;
    }

    public function setEntityClass(string $entityClass): self
    {
        $this->entityClass = $entityClass;

        return $this;
    }

    public function getEntityId(): ?int
    {
        return $this->entityId;
    }

    public function setEntityId(?int $entityId): self
    {
        $this->entityId = $entityId;

        return $this;
    }

    public function getPage(): ?int
    {
        return $this->page;
    }

    public function setPage(?int $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }
}
