<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Customer;
use App\HAL\HalPaginator;
use App\HAL\HalRessource;
use App\Service\Paginator;
use App\HAL\HalRessourceMaker;
use App\DTO\Customer\ReadCustomer;
use App\DTO\Customer\CreateCustomer;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use App\DTO\Customer\ReadLightCustomerDataTransformer;

class CustomerManager
{
    private CustomerRepository $repository;
    private EntityManagerInterface $entityManager;
    private HalRessourceMaker $halMaker;
    private Security $security;
    private Paginator $paginator;

    public function __construct(
        CustomerRepository $repository,
        EntityManagerInterface $entityManager,
        HalRessourceMaker $halMaker,
        Security $security,
        Paginator $paginator
    ) {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->halMaker = $halMaker;
        $this->security = $security;
        $this->paginator = $paginator;
    }

    /**
     * Return an array of Customer objects with pagination.
     */
    public function getPaginatedCustomers(int $page): ?HalPaginator
    {
        $user = $this->security->getUser();

        if ($user instanceof User) {
            $paginator = $this->paginator->paginate(
                $this->repository->findAllCustomersByUserQuery($user),
                $page,
                4,
                new ReadLightCustomerDataTransformer()
            );

            return $this->halMaker->makePaginatorRessource($paginator);
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
    public function getReadCustomer(Customer $customer): HalRessource
    {
        return $this->convertToHalRessource($customer);
    }

    /**
     * Add a new Customer object in database.
     */
    public function addNewCustomer(CreateCustomer $createCustomer): HalRessource
    {
        $user = $this->security->getUser();
        $customer = $createCustomer->createCustomer();

        if ($user instanceof User) {
            $customer->setUser($user);
            $this->entityManager->persist($customer);
            $this->entityManager->flush();
        }

        return $this->convertToHalRessource($customer);
    }

    /**
     * Delete one Customer for a given id and User.
     */
    public function delete(Customer $customer): HalRessource
    {
        // execute the line below before flush because "id" won't be initialized anymore
        $readCustomer = $this->convertToHalRessource($customer);
        ;

        $this->entityManager->remove($customer);
        $this->entityManager->flush();

        return $readCustomer;
    }

    /**
     * Convert a Customer to Customer HalRessource.
     */
    private function convertToHalRessource(Customer $customer): HalRessource
    {
        return $this->halMaker->makeRessource(ReadCustomer::createFromCustomer($customer));
    }
}
