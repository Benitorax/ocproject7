<?php

namespace App\Service;

use App\HAL\HalPaginator;
use App\HAL\HalRessource;
use App\Service\Paginator;
use App\DTO\Phone\ReadPhone;
use App\HAL\HalRessourceMaker;
use App\Repository\PhoneRepository;
use App\DTO\Phone\ReadLightPhoneDataTransformer;

class PhoneManager
{
    private PhoneRepository $repository;
    private HalRessourceMaker $halMaker;
    private Paginator $paginator;

    public function __construct(
        PhoneRepository $repository,
        HalRessourceMaker $halMaker,
        Paginator $paginator
    ) {
        $this->repository = $repository;
        $this->halMaker = $halMaker;
        $this->paginator = $paginator;
    }

    /**
     * Return an array of Phone objects with pagination.
     */
    public function getPaginatedPhones(int $page): HalPaginator
    {
        $paginator = $this->paginator->paginate(
            $this->repository->findAllTricksQuery(),
            $page,
            4,
            new ReadLightPhoneDataTransformer()
        );

        return $this->halMaker->makePaginatorRessource($paginator);
    }

    /**
     * Return a Phone object from the given id.
     */
    public function getPhoneById(int $id): ?HalRessource
    {
        $phone = $this->repository->findOneBy(['id' => $id]);

        if (null !== $phone) {
            return $this->halMaker->makeRessource(ReadPhone::createFromPhone($phone));
        }

        return null;
    }
}
