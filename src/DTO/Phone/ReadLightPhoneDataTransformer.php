<?php

namespace App\DTO\Phone;

use App\Entity\Phone;
use App\DTO\Phone\ReadLightPhone;
use App\DTO\DataTransformerInterface;

class ReadLightPhoneDataTransformer implements DataTransformerInterface
{
    /**
     * Transform a Phone object(s) to ReadLightPhone object(s).
     *
     * @param Phone|Phone[] $data
     * @return ReadLightPhone|ReadLightPhone[]
     */
    public function transform($data)
    {
        if (is_array($data)) {
            return $this->transformPhones($data);
        }

        if ($data instanceof Phone) {
            return $this->transformPhone($data);
        }
    }

    /**
     * Transform a Phone object to ReadLightPhone object.
     */
    public function transformPhone(Phone $phone): ReadLightPhone
    {
        return ReadLightPhone::createFromPhone($phone);
    }

    /**
     * Transform a Phone objects to ReadLightPhone objects.
     * @param Phone[] $phones
     * @return ReadLightPhone[]
     */
    public function transformPhones($phones)
    {
        $readPhones = [];

        foreach ($phones as $phone) {
            $readPhones[] = $this->transformPhone($phone);
        }

        return $readPhones;
    }
}
