<?php

namespace App\Entity;

use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @OA\Property(
     *    description="Email address.",
     *    example="jeremy.jenkins@example.com"
     * )
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
     * @OA\Property(
     *    description="Phone number.",
     *    example="+13521371036"
     * )
     */
    private string $phoneNumber;

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }
}
