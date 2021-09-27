<?php

namespace App\DTO;

interface DTOInterface
{
    /**
     * Return the id of the ressource
     */
    public function getId(): int;

    /**
     * Return the entity name of the ressource
     */
    public function entityName(): string;
}
