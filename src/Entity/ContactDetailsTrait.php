<?php

namespace App\Entity;

trait ContactDetailsTrait
{

    /**
     * @Assert\Length(
     *      min = 10,
     *      max = 255,
     *      minMessage = "Your email must be at least {{ limit }} characters long",
     *      maxMessage = "Your email cannot be longer than {{ limit }} characters"
     * )
     * @ORM\Column(type="string", length=255)
     */
    private string $email;

    /**
     * @Assert\Length(
     *      min = 10,
     *      max = 20,
     *      minMessage = "Your phone number must be at least {{ limit }} characters long",
     *      maxMessage = "Your phone number cannot be longer than {{ limit }} characters"
     * )
     * @ORM\Column(type="string", length=20)
     */
    private string $phoneNumber;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }
}
