<?php

namespace App\DTO\Address;

use App\Entity\Address;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema
 */
class DTOAddress
{
    /**
     * @OA\Property(
     *    description="Street address.",
     *    example="8364 Neva Light"
     * )
     */
    private string $address;

    /**
     * @OA\Property(
     *    description="City.",
     *    example="Hillland"
     * )
     */
    private string $city;

    /**
     * @OA\Property(
     *    description="ZIP code.",
     *    example="88619-7139"
     * )
     */
    private string $zipCode;

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getZipCode(): string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public static function createFromAddress(Address $address): self
    {
        return (new self())
            ->setAddress($address->getAddress())
            ->setCity($address->getCity())
            ->setZipCode($address->getZipCode())
        ;
    }

    public function createAddress(): Address
    {
        return (new Address())
            ->setAddress($this->address)
            ->setCity($this->city)
            ->setZipCode($this->zipCode)
        ;
    }
}
