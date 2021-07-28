<?php

namespace App\DTO\Customer;

use App\Entity\Customer;
use OpenApi\Annotations as OA;
use App\DTO\Address\DTOAddress;
use App\DTO\ContactDetailsTrait;

/**
 * @OA\Schema()
 */
class CreateCustomer
{
    use ContactDetailsTrait;

    /**
     * @OA\Property(
     *    description="Customer gender (Mr., Ms. or miss).",
     *    example="Ms.",
     *    enum={"Mr.", "Ms.", "Miss"},
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

    private DTOAddress $address;

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

    public function getAddress(): ?DTOAddress
    {
        return $this->address;
    }

    public function setAddress(DTOAddress $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function createCustomer(): Customer
    {
        return (new Customer())
            ->setGender($this->gender)
            ->setFirstName($this->firstName)
            ->setLastName($this->lastName)
            ->setAddress($this->address->createAddress())
            ->setPhoneNumber($this->phoneNumber)
            ->setEmail($this->email)
        ;
    }
}
