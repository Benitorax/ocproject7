<?php

namespace App\HAL;

use App\DTO\DTOInterface;
use App\HAL\HalPaginator;
use App\HAL\HalRessource;
use App\Service\Paginator;
use App\HAL\RessourceLinkMaker;
use Symfony\Component\Serializer\SerializerInterface;

class HalRessourceMaker
{
    private RessourceLinkMaker $linkMaker;
    private SerializerInterface $serializer;

    public function __construct(
        RessourceLinkMaker $linkMaker,
        SerializerInterface $serializer
    ) {
        $this->linkMaker = $linkMaker;
        $this->serializer = $serializer;
    }

    /**
     * Create HalPaginator object from a Paginator object.
     *
     * Paramater $linksToCreate should have this kind of format:
     * [
     *     'list' => ['api_phone_index', [], 'GET'],
     *     'self' => ['api_phone_show', ['id'], 'GET'],
     * ]
     */
    public function makePaginatorRessource(Paginator $paginator, array $linksToCreate): string
    {
        // transform items to HalRessource objects
        $items = [];
        foreach ($paginator->getItems() as $item) {
            $items[] = $this->makeLightRessource($item, $linksToCreate['self']);
        }

        $paginator = (new HalPaginator())
            ->setCount($paginator->count())
            ->setTotal($paginator->getItemsTotal())
            ->setEmbedded($items)
            ->setLinks($this->linkMaker->makePaginationLinks($paginator, $linksToCreate['list'][0]))
        ;

        return $this->serializer->serialize($paginator, 'json');
    }

    /**
     * Create HalRessource object from a given ressource object.
     *
     * Paramater $linksToCreate should have this kind of format:
     * [
     *     'self' => ['api_customer_show', ['id'], 'GET'],
     *     'list' => ['api_customer_index', [], 'GET'],
     *     'create' => ['api_customer_create', [], 'POST'],
     *     'delete' => ['api_customer_delete', ['id'], 'DELETE'],
     * ]
     */
    public function makeRessource(DTOInterface $ressource, array $linksToCreate): string
    {
        $ressource = (new HalRessource())
            ->setId($ressource->getId())
            ->settype($ressource->getEntityName())
            ->setRessource($ressource)
            ->setLinks($this->linkMaker->makeLinks($ressource, $linksToCreate))
        ;

        return $this->serializer->serialize($ressource, 'json');
    }

    /**
     * Create HalRessource object from a given ressource object.
     * Used for paginated items.
     *
     * Paramater $linksToCreate should have this kind of format:
     * ['api_phone_show', ['id'], 'GET']
     */
    private function makeLightRessource(DTOInterface $ressource, array $linksToCreate): HalRessource
    {
        return (new HalRessource())
            ->setId($ressource->getId())
            ->settype($ressource->getEntityName())
            ->setRessource($ressource)
            ->setLinks($this->linkMaker->makeSelfLink($ressource, $linksToCreate))
        ;
    }
}
