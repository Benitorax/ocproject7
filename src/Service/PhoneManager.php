<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Phone;
use App\Service\Paginator;
use App\DTO\Phone\ReadPhone;
use App\HAL\HalRessourceMaker;
use App\Service\RessourceCache;
use App\Repository\PhoneRepository;
use Symfony\Component\Security\Core\Security;
use App\DTO\Phone\ReadLightPhoneDataTransformer;

class PhoneManager
{
    private PhoneRepository $repository;
    private HalRessourceMaker $halMaker;
    private RessourceCache $cache;
    private Paginator $paginator;
    private User $user;

    public function __construct(
        PhoneRepository $repository,
        HalRessourceMaker $halMaker,
        RessourceCache $cache,
        Paginator $paginator,
        Security $security
    ) {
        $this->repository = $repository;
        $this->halMaker = $halMaker;
        $this->cache = $cache;
        $this->paginator = $paginator;

        /** @var User */
        $user = $security->getUser();
        $this->user = $user;
    }

    /**
     * Get etag of several phones.
     */
    public function getPhonesEtag(int $page): string
    {
        $paginator = $this->paginator->paginate(
            $this->repository->findAllTricksQuery(),
            $page,
            4
        );

        // prevDatetime will be the most recent date
        $prevDatetime = null;
        foreach ($paginator->getItems() as $phone) {
            $nextDatetime = $phone->getUpdatedAt();
            if ($nextDatetime > $prevDatetime) {
                $prevDatetime = $nextDatetime;
            }
        }

        return md5($prevDatetime->format('Y-m-d H:i:s'));
    }

    /**
     * Return a cached collection of phones with pagination from the given page
     */
    public function getCachePaginatedPhones(int $page): string
    {
        return $this->cache->get($this->user->getId(), Phone::class, null, $page);
    }

    /**
     * Return a collection of phones with pagination from the given page.
     */
    public function getPaginatedPhones(int $page): string
    {
        $paginator = $this->paginator->paginate(
            $this->repository->findAllTricksQuery(),
            $page,
            4,
            new ReadLightPhoneDataTransformer()
        );

        $phones = $this->halMaker->makePaginatorRessource($paginator);
        $this->cache->cache($this->user->getId(), Phone::class, null, $page, $phones);

        return $phones;
    }

    /**
     * Return a cached phone from the given Phone object.
     */
    public function getCacheReadPhone(Phone $phone): string
    {
        return $this->cache->get($this->user->getId(), Phone::class, $phone->getId());
    }

    /**
     * Return a phone from the given Phone object.
     */
    public function getReadPhone(Phone $phone): string
    {
        $halPhone = $this->convertToHalRessource($phone);
        $this->cache->cache($this->user->getId(), Phone::class, $phone->getId(), null, $halPhone);

        return $halPhone;
    }

    /**
     * Convert a Phone to Customer HalRessource.
     */
    private function convertToHalRessource(Phone $phone): string
    {
        return $this->halMaker->makeRessource(ReadPhone::createFromPhone($phone));
    }
}
