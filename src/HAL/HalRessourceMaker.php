<?php

namespace App\HAL;

use App\HAL\HalPaginator;
use App\HAL\HalRessource;
use App\Service\Paginator;
use App\DTO\Phone\ReadPhone;
use App\HAL\RessourceLinkMaker;
use App\DTO\Phone\ReadLightPhone;
use App\DTO\Customer\ReadCustomer;
use App\DTO\Customer\ReadLightCustomer;
use Symfony\Component\Serializer\SerializerInterface;

class HalRessourceMaker
{
    public const TYPES = [
        ReadLightCustomer::class => 'Customer',
        ReadCustomer::class => 'Customer',
        ReadLightPhone::class => 'Phone',
        ReadPhone::class => 'Phone',
    ];
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
     */
    public function makePaginatorRessource(Paginator $paginator): string
    {
        // transform items to HalRessource objects
        $items = [];
        foreach ($paginator->getItems() as $item) {
            $items[] = $this->makeLightRessource($item);
        }

        $paginator = (new HalPaginator())
            ->setCount($paginator->count())
            ->setTotal($paginator->getItemsTotal())
            ->setEmbedded($items)
            ->setLinks($this->linkMaker->makePaginationLinks($paginator))
        ;

        return $this->serializer->serialize($paginator, 'json');
    }

    /**
     * Create HalRessource object from a given ressource object.
     */
    public function makeRessource(object $ressource): string
    {
        if (!method_exists($ressource, 'getId')) {
            throw new \Exception('Ressource object must define getId method to generate id for HAL ressource.');
        }

        $ressource = (new HalRessource())
            ->setId($ressource->getId())
            ->settype($this->getEntityName($ressource))
            ->setRessource($ressource)
            ->setLinks($this->linkMaker->makeLinks($ressource))
        ;

        return $this->serializer->serialize($ressource, 'json');
    }

    /**
     * Create HalRessource object from a given ressource object.
     * Used for paginated items.
     */
    private function makeLightRessource(object $ressource): HalRessource
    {
        if (!method_exists($ressource, 'getId')) {
            throw new \Exception('Ressource object must define getId method to generate id for HAL ressource.');
        }

        return (new HalRessource())
            ->setId($ressource->getId())
            ->settype($this->getEntityName($ressource))
            ->setRessource($ressource)
            ->setLinks($this->linkMaker->makeSelfLink($ressource))
        ;
    }

    /**
     * Return only the class name of the entity: the FQCN without the namespaces.
     */
    private function getEntityName(object $ressource): string
    {
        return self::TYPES[get_class($ressource)];
    }
}
