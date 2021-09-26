<?php

namespace App\Controller;

use App\Entity\Phone;
use App\DTO\Phone\ReadPhone;
use App\Service\PhoneManager;
use OpenApi\Annotations as OA;
use App\Controller\AppAbstractController;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PhoneController extends AppAbstractController
{
    /**
     * Show phones.
     *
     * @Route("/api/phones", name="api_phone_index", methods={"GET"})
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns a paginated list of phones",
     *     @OA\JsonContent(@OA\Schema(
     *       type="array",
     *       @OA\Items(ref=@Model(type=ReadPhone::class))
     *     ))
     * )
     * @OA\Tag(name="phones")
     */
    public function index(Request $request, PhoneManager $manager): Response
    {
        $page = (int) $request->query->get('page') ?: 1;
        $etag = $manager->getPhonesEtag($page);

        if (null !== $etag && $this->isResponseNotModified($etag, $request)) {
            $cachePhones = $manager->getCachePaginatedPhones($page);
            return $this->jsonResponseWithEtag($cachePhones, $etag);
        }

        $phones = $manager->getPaginatedPhones($page);

        return $this->jsonResponseWithEtag($phones, $etag);
    }

    /**
     * Show phone.
     *
     * @Route("/api/phones/{id}", name="api_phone_show", methods={"GET"})
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns detailed informations about a phone",
     *     @OA\JsonContent(
     *       ref=@Model(type=ReadPhone::class)
     *     )
     * )
     * @OA\Response(response=404, description="Phone not found.")
     * @OA\Parameter(ref="#/components/parameters/id")
     * @OA\Tag(name="phones")
     */
    public function show(Phone $phone, PhoneManager $manager, Request $request): Response
    {
        $etag = $this->getEntityEtag($phone);

        if ($this->isResponseNotModified($etag, $request)) {
            $cachePhone = $manager->getCacheReadPhone($phone);
            return $this->jsonResponseWithEtag($cachePhone, $etag);
        }

        $phone = $manager->getReadPhone($phone);

        return $this->jsonResponseWithEtag($phone, $etag);
    }
}
