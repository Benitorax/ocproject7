<?php

namespace App\HAL;

use App\HAL\Link;
use App\Service\Paginator;
use App\DTO\Phone\ReadPhone;
use App\DTO\Phone\ReadLightPhone;
use App\DTO\Customer\ReadCustomer;
use App\DTO\Customer\ReadLightCustomer;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RessourceLinkMaker
{
    public const PAGINATION_RESSOURCES = [
        ReadLightCustomer::class => [
           'list' => ['api_customer_index', [], 'GET'],
           'self' => ['api_customer_show', ['id'], 'GET'],
        ],
        ReadLightPhone::class => [
           'list' => ['api_phone_index', [], 'GET'],
           'self' => ['api_phone_show', ['id'], 'GET'],
        ],
    ];

    public const RESSOURCES = [
        ReadCustomer::class => [
            'self' => ['api_customer_show', ['id'], 'GET'],
            'list' => ['api_customer_index', [], 'GET'],
            'create' => ['api_customer_create', [], 'POST'],
            'delete' => ['api_customer_delete', ['id'], 'DELETE'],
        ],
        ReadPhone::class => [
            'self' => ['api_phone_show', ['id'], 'GET'],
            'list' => ['api_phone_index', [], 'GET'],
        ],
    ];

    private UrlGeneratorInterface $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    /**
     * Generate links for pagination.
     *
     * @return Link[]
     */
    public function makePaginationLinks(Paginator $paginator)
    {
        $ressource = $paginator->getItems()[0];
        $data = self::PAGINATION_RESSOURCES[get_class($ressource)]['list'];
        $url = $this->generateUrl($data[0]);
        $page = $paginator->getPage();
        $pagesTotal = $paginator->getPagesTotal();

        $links = [];
        $links['self'] = $this->makePaginationLink($url . '?page=' . $page);
        $links['first'] = $this->makePaginationLink($url);

        if ($page > 1) {
            $links['prev'] = $this->makePaginationLink($url . '?page=' . ($page - 1));
        }

        if ($page < $pagesTotal) {
            $links['next'] = $this->makePaginationLink($url . '?page=' . ($page + 1));
        }

        $links['last'] = $this->makePaginationLink($url . '?page=' . $pagesTotal);

        return $links;
    }

    /**
     * Create Link objects for a ressource.
     *
     * @return Link[]
     */
    public function makeLinks(object $ressource)
    {
        if (!method_exists($ressource, 'getId')) {
            throw new \Exception('Ressource object must define getId method to generate id for HAL ressource.');
        }

        $ressources = self::RESSOURCES[get_class($ressource)];
        $links = [];
        foreach ($ressources as $name => $data) {
            $links[$name] = $this->makeLink($data, $ressource->getId());
        }

        return $links;
    }

    /**
     * Create a Link object for a ressource.
     *
     * @return Link[]
     */
    public function makeSelfLink(object $ressource)
    {
        if (!method_exists($ressource, 'getId')) {
            throw new \Exception('Ressource object must define getId method to generate id for HAL ressource.');
        }

        $data = self::PAGINATION_RESSOURCES[get_class($ressource)]['self'];
        return ['self' => $this->makeLink($data, $ressource->getId())];
    }

    /**
     * Create a Link object.
     */
    public function makeLink(array $data, int $id): Link
    {
        $params = [];
        if (!empty($data[1])) {
            $params['id'] = $id;
        }

        return (new Link())
            ->setHref($this->generateUrl($data[0], $params))
            ->setMethod($data[2])
        ;
    }

    /**
     * Create a Link for pagination with given url.
     */
    public function makePaginationLink(string $url): Link
    {
        return (new Link())
            ->setHref($url)
            ->setMethod('GET')
        ;
    }

    /**
     * Generate url.
     */
    public function generateUrl(string $routeName, array $parameters = []): string
    {
        return $this->router->generate($routeName, $parameters);
    }
}
