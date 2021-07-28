<?php

namespace App\DTO;

use OpenApi\Annotations as OA;

trait ContactDetailsTrait
{

    /**
     * @OA\Property(
     *    description="Email address.",
     *    example="jeremy.jenkins@example.com"
     * )
     */
    private string $email;

    /**
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
