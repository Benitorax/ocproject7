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

class HalRessourceMaker
{
    public const TYPES = [
        ReadLightCustomer::class => 'Customer',
        ReadCustomer::class => 'Customer',
        ReadLightPhone::class => 'Phone',
        ReadPhone::class => 'Phone',
    ];
    private RessourceLinkMaker $linkMaker;

    public function __construct(RessourceLinkMaker $linkMaker)
    {
        $this->linkMaker = $linkMaker;
    }

    /**
     * Create HalPaginator object from a Paginator object.
     */
    public function makePaginatorRessource(Paginator $paginator): HalPaginator
    {
        // transform items to HalRessource objects
        $items = [];
        foreach ($paginator->getItems() as $item) {
            $items[] = $this->makeLightRessource($item);
        }

        return (new HalPaginator())
            ->setCount($paginator->count())
            ->setTotal($paginator->getItemsTotal())
            ->setEmbedded($items)
            ->setLinks($this->linkMaker->makePaginationLinks($paginator))
        ;
    }

    /**
     * Create HalRessource object from a given ressource object.
     */
    public function makeRessource(object $ressource): HalRessource
    {
        if (!method_exists($ressource, 'getId')) {
            throw new \Exception('Ressource object must define getId method to generate id for HAL ressource.');
        }

        return (new HalRessource())
            ->setId($ressource->getId())
            ->settype($this->getClassName($ressource))
            ->setRessource($ressource)
            ->setLinks($this->linkMaker->makeLinks($ressource))
        ;
    }

    /**
     * Create HalRessource object from a given ressource object.
     */
    public function makeLightRessource(object $ressource): HalRessource
    {
        if (!method_exists($ressource, 'getId')) {
            throw new \Exception('Ressource object must define getId method to generate id for HAL ressource.');
        }

        return (new HalRessource())
            ->setId($ressource->getId())
            ->settype($this->getClassName($ressource))
            ->setRessource($ressource)
            ->setLinks($this->linkMaker->makeSelfLink($ressource))
        ;
    }

    /**
     * Return only the class name: the FQCN without the namespaces.
     */
    private function getClassName(object $ressource): string
    {
        // $fqcn = get_class($ressource);

        // // remove the namespaces
        // return substr($fqcn, strrpos($fqcn, '\\') + 1);

        return self::TYPES[get_class($ressource)];
    }
}
