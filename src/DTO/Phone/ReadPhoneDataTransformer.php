<?php

namespace App\DTO\Phone;

use App\Entity\Phone;
use App\DTO\Phone\ReadPhone;
use App\DTO\DataTransformerInterface;

class ReadPhoneDataTransformer implements DataTransformerInterface
{
    /**
     * Transform a Phone object(s) to ReadPhone object(s).
     *
     * @param Phone|Phone[] $data
     * @return ReadPhone|ReadPhone[]
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
     * Transform a Phone object to ReadPhone object.
     */
    public function transformPhone(Phone $phone): ReadPhone
    {
        return ReadPhone::createFromPhone($phone);
    }

    /**
     * Transform a Phone objects to ReadPhone objects.
     * @param Phone[] $phones
     * @return ReadPhone[]
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
