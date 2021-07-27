<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AddressRepository;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AddressRepository::class)
 */
class Address
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @Assert\Length(
     *      min = 10,
     *      max = 255,
     *      minMessage = "Your address must be at least {{ limit }} characters long",
     *      maxMessage = "Your address cannot be longer than {{ limit }} characters"
     * )
     * @ORM\Column(type="string", length=255)
     */
    private string $address;

    /**
     * @Assert\Length(
     *      min = 4,
     *      max = 100,
     *      minMessage = "Your city must be at least {{ limit }} characters long",
     *      maxMessage = "Your city cannot be longer than {{ limit }} characters"
     * )
     * @ORM\Column(type="string", length=100)
     */
    private string $city;

    /**
     * @Assert\Length(
     *      min = 10,
     *      max = 10,
     *      minMessage = "Your ZIP code name must be at least {{ limit }} characters long",
     *      maxMessage = "Your ZIP code name cannot be longer than {{ limit }} characters"
     * )
     * @ORM\Column(type="string", length=10)
     */
    private string $zipCode;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }
}
