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

        $cachePhones = $manager->getCachePaginatedPhones($page);
        $response = $this->jsonResponseWithEtag($cachePhones);

        if ($response->isNotModified($request)) {
            return $response;
        }

        $phones = $manager->getPaginatedPhones($page);

        return $this->jsonResponseWithEtag($phones);
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
     * @OA\Response(response=403, description="Access denied.")
     * @OA\Response(response=404, description="Phone not found.")
     * @OA\Parameter(ref="#/components/parameters/id")
     * @OA\Tag(name="phones")
     */
    public function show(Phone $phone, PhoneManager $manager, Request $request): Response
    {
        $cachePhone = $manager->getCacheReadPhone($phone);
        $response = $this->jsonResponseWithEtag($cachePhone);

        if ($response->isNotModified($request)) {
            return $response;
        }

        $phone = $manager->getReadPhone($phone);

        return $this->jsonResponseWithEtag($phone);
    }
}
