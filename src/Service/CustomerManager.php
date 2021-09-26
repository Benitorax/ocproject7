<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Customer;
use App\Service\Paginator;
use App\HAL\HalRessourceMaker;
use App\Service\RessourceCache;
use App\DTO\Customer\ReadCustomer;
use App\DTO\Customer\CreateCustomer;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use App\DTO\Customer\ReadLightCustomerDataTransformer;

class CustomerManager
{
    public const PAGINATION_LINKS = [
        'list' => ['api_customer_index', [], 'GET'],
        'self' => ['api_customer_show', ['id'], 'GET'],
    ];

    public const RESSOURCE_LINKS = [
        'self' => ['api_customer_show', ['id'], 'GET'],
        'list' => ['api_customer_index', [], 'GET'],
        'create' => ['api_customer_create', [], 'POST'],
        'delete' => ['api_customer_delete', ['id'], 'DELETE'],
    ];

    private CustomerRepository $repository;
    private RessourceCache $cache;
    private EntityManagerInterface $entityManager;
    private HalRessourceMaker $halMaker;
    private Paginator $paginator;
    private User $user;

    public function __construct(
        CustomerRepository $repository,
        RessourceCache $cache,
        EntityManagerInterface $entityManager,
        HalRessourceMaker $halMaker,
        Security $security,
        Paginator $paginator
    ) {
        $this->repository = $repository;
        $this->cache = $cache;
        $this->entityManager = $entityManager;
        $this->halMaker = $halMaker;
        $this->paginator = $paginator;

        /** @var User */
        $user = $security->getUser();
        $this->user = $user;
    }

    /**
     * Get etag of several phones.
     */
    public function getCustomersEtag(int $page): ?string
    {
        $paginator = $this->paginator->paginate(
            $this->repository->findAllCustomersByUserQuery($this->user),
            $page,
            4
        );
        $items = $paginator->getItems();
        $etag = null;

        if (count($items) > 0) {
            $etag = md5(serialize($items));
        }

        return $etag;
    }

    /**
     * Return a cached collection of customers with pagination from the given page.
     */
    public function getCachePaginatedCustomers(int $page): string
    {
        return $this->cache->get($this->user->getId(), Customer::class, null, $page);
    }

    /**
     * Return a collection of customers with pagination from the given page.
     */
    public function getPaginatedCustomers(int $page): string
    {
        $paginator = $this->paginator->paginate(
            $this->repository->findAllCustomersByUserQuery($this->user),
            $page,
            4,
            new ReadLightCustomerDataTransformer()
        );

        $halCustomers = $this->halMaker->makePaginatorRessource($paginator, self::PAGINATION_LINKS);
        $this->cache->cache($this->user->getId(), Customer::class, null, $page, $halCustomers);

        return $halCustomers;
    }

    /**
     * Return a cached Customer from the given Customer object.
     */
    public function getCacheReadCustomer(Customer $customer): string
    {
        return $this->cache->get($this->user->getId(), Customer::class, $customer->getId());
    }

    /**
     * If owned by the given User, return a Customer from the given Customer object.
     */
    public function getReadCustomer(Customer $customer): string
    {
        $halCustomer = $this->convertToHalRessource($customer);
        $this->cache->cache($this->user->getId(), Customer::class, $customer->getId(), null, $halCustomer);

        return $halCustomer;
    }

    /**
     * Add a new Customer object in database.
     */
    public function addNewCustomer(CreateCustomer $createCustomer): string
    {
        $customer = $createCustomer->createCustomer();
        $customer->setUser($this->user);
        $this->entityManager->persist($customer);
        $this->entityManager->flush();

        return $this->convertToHalRessource($customer);
    }

    /**
     * Delete one Customer for a given id and User.
     */
    public function delete(Customer $customer): string
    {
        // execute the line below before flush because "id" won't be initialized anymore
        $readCustomer = $this->convertToHalRessource($customer);

        $this->entityManager->remove($customer);
        $this->entityManager->flush();

        return $readCustomer;
    }

    /**
     * Convert a Customer to Customer HalRessource.
     */
    private function convertToHalRessource(Customer $customer): string
    {
        return $this->halMaker->makeRessource(ReadCustomer::createFromCustomer($customer), self::RESSOURCE_LINKS);
    }
}
