<?php

namespace App\Service;

use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\CacheInterface;

class RessourceCache
{
    private CacheInterface $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
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
        $key =  $this->createKey($userId, $entityClass, $entityId, $page);

        $this->cache->delete($key);
        $this->cache->get($key, function () use ($content) {
            return $content;
        });
    }

    /**
     * Get the ressource from database.
     */
    public function get(int $userId, string $entityClass, ?int $entityId = null, ?int $page = null): string
    {
        $item = $this->cache->get(
            $this->createKey($userId, $entityClass, $entityId, $page),
            function (ItemInterface $item) {
                return $item;
            }
        );

        if ($item !== null) {
            return $item;
        }

        return '';
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
     * Create a key from user id, entity class name, entity id or page number.
     */
    public function createKey(int $userId, string $entityClass, ?int $entityId = null, ?int $page = null): string
    {
        $this->checkEntityIdAndPage($entityId, $page);

        // remove the FQCN of entity class name
        $className = substr($entityClass, strrpos($entityClass, '\\') + 1);

        if (null !== $page) {
            return sprintf('user%d_%s_index_page%d', $userId, $className, $page);
        }

        return sprintf('user%d_%s%d', $userId, $className, $entityId);
    }
}
