<?php

namespace App\Service;

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
    public function getPaginatedCustomers(int $start, int $limit): ?Paginator
    {
        $user = $this->security->getUser();
        if ($user instanceof User) {
            return $this->paginator->paginate(
                $this->repository->findAllCustomersByUserQuery($user),
                $start,
                $limit
            );
        }

        return null;
    }

    /**
     * If owned by the given User, return a Customer object from the given id.
     */
    public function getCustomerByIdAndUser(int $id): ?Customer
    {
        $user = $this->security->getUser();
        if ($user instanceof User) {
            return $this->repository->findOneByIdAndUser($id, $user);
        }

        return null;
    }
}
