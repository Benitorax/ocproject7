<?php

namespace App\Service;

use App\DTO\Customer\ReadCustomerDataTransformer;
use App\Entity\User;
use App\Entity\Customer;
use App\Service\Paginator;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;

class CustomerManager
{
    private CustomerRepository $repository;
    private EntityManagerInterface $entityManager;
    private SerializerInterface $serializer;
    private Security $security;
    private Paginator $paginator;

    public function __construct(
        CustomerRepository $repository,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        Security $security,
        Paginator $paginator
    ) {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->security = $security;
        $this->paginator = $paginator;
    }

    /**
     * Return an array of Customer objects with pagination.
     */
    public function getPaginatedCustomers(int $page): ?Paginator
    {
        $user = $this->security->getUser();

        if ($user instanceof User) {
            return $this->paginator->paginate(
                $this->repository->findAllCustomersByUserQuery($user),
                $page,
                5,
                new ReadCustomerDataTransformer()
            );
        }

        return null;
    }

    /**
     * Return a Customer object from the given id.
     */
    public function getOneById(int $id): ?Customer
    {
        return $this->repository->findOneBy(['id' => $id]);
    }

    /**
     * If owned by the given User, return a Customer object from the given id.
     */
    public function getOneByIdAndUser(int $id): ?Customer
    {
        $user = $this->security->getUser();

        if ($user instanceof User) {
            return $this->repository->findOneByIdAndUser($id, $user);
        }

        return null;
    }

    /**
     * Add a new Customer object in database.
     */
    public function addNewCustomer(Customer $customer): Customer
    {
        $user = $this->security->getUser();

        if ($user instanceof User) {
            $customer->setUser($user);
            $this->entityManager->persist($customer);
            $this->entityManager->flush();
        }

        return $customer;
    }

    /**
     * Delete one Customer for a given id and User.
     */
    public function delete(Customer $customer): void
    {
        $this->entityManager->remove($customer);
        $this->entityManager->flush();
    }
}
