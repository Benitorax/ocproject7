<?php

namespace App\DTO\Phone;

use App\DTO\DTOInterface;
use App\Entity\Phone;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema
 */
class ReadPhone implements DTOInterface
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

    /**
     * @OA\Property(
     *     description="Screen size (inch).",
     *     example="6.8"
     * )
     */
    private float $screenSize;

    /**
     * @OA\Property(
     *     description="Phone weight (g).",
     *     example="192"
     * )
     */
    private int $weight;

    /**
     * @OA\Property(
     *     description="Phone storage (Gb).",
     *     example="256"
     * )
     */
    private int $storage;

    /**
     * @OA\Property(
     *     description="Phone battery (mAh).",
     *     example="4500"
     * )
     */
    private int $battery;

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

    public function getScreenSize(): float
    {
        return $this->screenSize;
    }

    public function setScreenSize(float $screenSize): self
    {
        $this->screenSize = $screenSize;

        return $this;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getStorage(): int
    {
        return $this->storage;
    }

    public function setStorage(int $storage): self
    {
        $this->storage = $storage;

        return $this;
    }

    public function getBattery(): int
    {
        return $this->battery;
    }

    public function setBattery(int $battery): self
    {
        $this->battery = $battery;

        return $this;
    }

    public static function createFromPhone(Phone $phone): self
    {
        return (new self())
            ->setId($phone->getId())
            ->setBrand($phone->getBrand())
            ->setModel($phone->getModel())
            ->setScreenSize($phone->getScreenSize())
            ->setWeight($phone->getWeight())
            ->setStorage($phone->getStorage())
            ->setBattery($phone->getBattery())
        ;
    }

    /**
     * Return the entity name of the DTO.
     */
    public function entityName(): string
    {
        return 'Phone';
    }
}
