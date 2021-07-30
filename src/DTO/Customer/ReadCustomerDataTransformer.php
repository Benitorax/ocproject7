<?php

namespace App\DTO\Customer;

use App\Entity\Customer;
use App\DTO\Customer\ReadCustomer;
use App\DTO\DataTransformerInterface;

class ReadCustomerDataTransformer implements DataTransformerInterface
{
    /**
     * Transform a Customer object(s) to ReadCustomer object(s).
     *
     * @param Customer|Customer[] $data
     * @return ReadCustomer|ReadCustomer[]
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
     * Transform a Customer object to ReadCustomer object.
     */
    public function transformCustomer(Customer $customer): ReadCustomer
    {
        return ReadCustomer::createFromCustomer($customer);
    }

    /**
     * Transform a Customer objects to ReadCustomer objects.
     * @param Customer[] $customers
     * @return ReadCustomer[]
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
