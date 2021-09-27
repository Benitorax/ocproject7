<?php

namespace App\DTO\Customer;

use App\Entity\Customer;
use OpenApi\Annotations as OA;
use App\DTO\Address\Address;
use App\DTO\ContactDetailsTrait;
use App\DTO\DTOInterface;

/**
 * @OA\Schema
 */
class ReadCustomer implements DTOInterface
{
    use ContactDetailsTrait;

    /**
     * @OA\Property(
     *    description="The unique identifier of the ressource.",
     *    example="42"
     * )
     */
    private int $id;

    /**
     * @OA\Property(
     *    description="Customer gender (Mr., Ms. or miss).",
     *    example="Ms.",
     *    enum={"Mr.", "Ms.", "Miss"}
     * )
     */
    private string $gender;

    /**
     * @OA\Property(
     *    description="First name.",
     *    example="Lisa"
     * )
     */
    private string $firstName;

    /**
     * @OA\Property(
     *    description="Last name.",
     *    example="Kudrow"
     * )
     */
    private string $lastName;

    private Address $address;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    public static function createFromCustomer(Customer $customer): self
    {
        return (new self())
            ->setId($customer->getId())
            ->setGender($customer->getGender())
            ->setFirstName($customer->getFirstName())
            ->setLastName($customer->getLastName())
            ->setAddress(Address::createFromAddress($customer->getAddress()))
            ->setPhoneNumber($customer->getPhoneNumber())
            ->setEmail($customer->getEmail())
        ;
    }

    /**
     * Return the entity name of the DTO.
     */
    public function entityName(): string
    {
        return 'Customer';
    }
}
