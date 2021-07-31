<?php

namespace App\DTO\Phone;

use App\Entity\Phone;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema
 */
class ReadLightPhone
{
    /**
     * @OA\Property(
      *    description="The unique identifier of the ressource.",
      *    example="42"
      * )
      */
    private int $id;

    /**
     * @OA\Property(
     *     description="Phone brand",
     *     example="Samsung"
     * )
     */
    private string $brand;

    /**
     * @OA\Property(
     *     description="Phone model",
     *     example="Galaxy S20"
     * )
     */
    private string $model;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public static function createFromPhone(Phone $phone): self
    {
        return (new self())
            ->setId($phone->getId())
            ->setBrand($phone->getBrand())
            ->setModel($phone->getModel())
        ;
    }
}
