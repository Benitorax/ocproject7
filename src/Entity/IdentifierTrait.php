<?php

namespace App\Entity;

trait IdentifierTrait
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    public function getId(): int
    {
        return $this->id;
    }
}
