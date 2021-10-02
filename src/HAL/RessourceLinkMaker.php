<?php

namespace App\HAL;

use App\DTO\DTOInterface;
use App\HAL\Link;
use App\Service\Paginator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RessourceLinkMaker
{
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
    public function makePaginationLinks(Paginator $paginator, string $routeName)
    {
        $url = $this->generateUrl($routeName);
        $page = $paginator->getPage();
        $pagesTotal = $paginator->getPagesTotal();

        $links = [];
        $links['self'] = $this->makePaginationLink($url . '?page=' . $page);

        if ($page !== 1) {
            $links['first'] = $this->makePaginationLink($url . '?page=1');
        }

        if ($page > 1) {
            $links['prev'] = $this->makePaginationLink($url . '?page=' . ($page - 1));
        }

        if ($page < $pagesTotal) {
            $links['next'] = $this->makePaginationLink($url . '?page=' . ($page + 1));
        }

        if ($page < $pagesTotal) {
            $links['last'] = $this->makePaginationLink($url . '?page=' . $pagesTotal);
        }

        return $links;
    }

    /**
     * Create Link objects for a ressource.
     *
     * @return Link[]
     */
    public function makeLinks(DTOInterface $ressource, array $linksToCreate)
    {
        $links = [];
        foreach ($linksToCreate as $name => $data) {
            $links[$name] = $this->makeLink($data, $ressource->getId());
        }

        return $links;
    }

    /**
     * Create a Link object for a ressource.
     *
     * @return Link[]
     */
    public function makeSelfLink(DTOInterface $ressource, array $selfLinkToCreate)
    {
        return ['self' => $this->makeLink($selfLinkToCreate, $ressource->getId())];
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
