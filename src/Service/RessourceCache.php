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
    public function cache(
        int $userId,
        string $entityClass,
        ?int $entityId = null,
        ?int $page = null,
        string $content
    ): void {
        $this->checkEntityIdAndPage($entityId, $page);
        $criteria = $this->buildCriteria($userId, $entityClass, $entityId, $page);
        $ressource = $this->repository->findOneBy($criteria);

        if (null === $ressource) {
            $ressource = (new CacheRessource())
                ->setUserId($userId)
                ->setEntityClass($entityClass)
                ->setEntityId($entityId)
                ->setPage($page)
            ;
        }

        $ressource
            ->setContent($content)
            ->setUpdatedAt(new \DateTimeImmutable())
        ;

        $this->entityManager->persist($ressource);
        $this->entityManager->flush();
    }

    /**
     * Get the ressource from database.
     */
    public function get(int $userId, string $entityClass, ?int $entityId = null, ?int $page = null): string
    {
        $this->checkEntityIdAndPage($entityId, $page);
        $criteria = $this->buildCriteria($userId, $entityClass, $entityId, $page);
        $cacheRessource = $this->repository->findOneBy($criteria);

        if (null !== $cacheRessource) {
            return $cacheRessource->getContent();
        }

        return '';
    }

    /**
     * Remove the ressource(s) from database.
     */
    public function delete(int $userId, string $entityClass, ?int $entityId = null, ?int $page = null): void
    {
        $this->checkEntityIdAndPage($entityId, $page);
        $criteria = $this->buildCriteria($userId, $entityClass, $entityId, $page);
        $ressources = $this->repository->findBy($criteria);

        foreach ($ressources as $ressource) {
            $this->entityManager->remove($ressource);
        }

        $this->entityManager->flush();
    }

    /**
     * Check whether a least one of the parameters is not null.
     */
    private function checkEntityIdAndPage(?int $entityId = null, ?int $page = null): void
    {
        if (null === $entityId && null === $page) {
            throw new \Exception('You need to set at least the id of the ressource'
                . ' or the page of the index ressource to cache.');
        }
    }

    /**
     * Build criteria for repository.
     */
    private function buildCriteria(int $userId, string $entityClass, ?int $entityId = null, ?int $page = null): array
    {
        $criteria = [];
        $criteria['entityClass'] = $entityClass;
        $criteria['userId'] = $userId;

        if (null !== $entityId) {
            $criteria['entityId'] = $entityId;
        }

        if (null !== $page) {
            $criteria['page'] = $page;
        }

        return $criteria;
    }
}
