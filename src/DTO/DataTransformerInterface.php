<?php

namespace App\DTO;

interface DataTransformerInterface
{
    /**
     * Transform object to DTO object.
     *
     * @param object[] $data
     * @return object[]
     */
    public function transform($data);
}
