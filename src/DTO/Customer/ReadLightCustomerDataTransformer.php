<?php

namespace App\DTO\Customer;

use App\Entity\Customer;
use App\DTO\Customer\ReadLightCustomer;
use App\DTO\DataTransformerInterface;

class ReadLightCustomerDataTransformer implements DataTransformerInterface
{
    /**
     * Transform a Customer object(s) to ReadLightCustomer object(s).
     *
     * @param Customer|Customer[] $data
     * @return ReadLightCustomer|ReadLightCustomer[]
     */
    public function transform($data)
    {
        if (is_array($data)) {
            return $this->transformCustomers($data);
        }

        if ($data instanceof Customer) {
            return $this->transformCustomer($data);
        }
    }

    /**
     * Transform a Customer object to ReadLightCustomer object.
     */
    public function transformCustomer(Customer $customer): ReadLightCustomer
    {
        return ReadLightCustomer::createFromCustomer($customer);
    }

    /**
     * Transform a Customer objects to ReadLightCustomer objects.
     * @param Customer[] $customers
     * @return ReadLightCustomer[]
     */
    public function transformCustomers($customers)
    {
        $readCustomers = [];

        foreach ($customers as $customer) {
            $readCustomers[] = $this->transformCustomer($customer);
        }

        return $readCustomers;
    }
}
