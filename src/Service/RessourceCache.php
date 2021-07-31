<?php

namespace App\Service;

use App\Entity\CacheRessource;
use App\Repository\CacheRessourceRepository;
use Doctrine\ORM\EntityManagerInterface;

class RessourceCache
{
    private CacheRessourceRepository $repository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        CacheRessourceRepository $repository,
        EntityManagerInterface $entityManager
    ) {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    /**
     * Save the ressource in database.
     */
    public function cache(string $entityClass, ?int $entityId = null, ?int $page = null, string $content): void
    {
        if (null === $entityId && null === $page) {
            throw new \Exception('You need to set at least the id of the ressource'
                . ' or the page of the index ressource to cache.');
        }

        $ressource = (new CacheRessource())
            ->setEntityClass($entityClass)
            ->setEntityId($entityId)
            ->setPage($page)
            ->setContent($content)
        ;

        $this->entityManager->persist($ressource);
        $this->entityManager->flush();
    }

    /**
     * Get the ressource from database.
     */
    public function get(string $entityClass, ?int $entityId = null, ?int $page = null): string
    {
        if (null === $entityId && null === $page) {
            throw new \Exception('You need to set at least the id of the ressource'
                . ' or the page of the index ressource to cache.');
        }

        $criteria = [];
        $criteria['entityClass'] = $entityClass;

        if (null !== $entityId) {
            $criteria['entityId'] = $entityId;
        }

        if (null !== $page) {
            $criteria['page'] = $page;
        }

        $cacheRessource = $this->repository->findOneBy($criteria);

        if (null !== $cacheRessource) {
            return $cacheRessource->getContent();
        }

        return '';
    }
}
