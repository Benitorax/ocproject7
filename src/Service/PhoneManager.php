<?php

namespace App\Service;

use App\Entity\Phone;
use App\Service\Paginator;
use App\Repository\PhoneRepository;
use Symfony\Component\Serializer\SerializerInterface;

class PhoneManager
{
    private PhoneRepository $repository;
    private SerializerInterface $serializer;
    private Paginator $paginator;

    public function __construct(
        PhoneRepository $repository,
        SerializerInterface $serializer,
        Paginator $paginator
    ) {
        $this->repository = $repository;
        $this->serializer = $serializer;
        $this->paginator = $paginator;
    }

    /**
     * Return an array of Phone objects with pagination.
     */
    public function getPaginatedPhones(int $start, int $limit): Paginator
    {
        return $this->paginator->paginate(
            $this->repository->findAllTricksQuery(),
            $start,
            $limit
        );
    }

    /**
     * Return a Phone object from the given id.
     */
    public function getPhoneById(int $id): ?Phone
    {
        return $this->repository->findOneBy(['id' => $id]);
    }
}
