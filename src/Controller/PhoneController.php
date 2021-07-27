<?php

namespace App\Controller;

use App\Service\PhoneManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PhoneController extends AbstractController
{
    /**
     * Show a paginated list of phones.
     *
     * @Route("/api/phone", name="phone_index", methods={"GET"})
     */
    public function index(Request $request, PhoneManager $manager): Response
    {
        $phones = $manager->getPaginatedPhones(
            (int) $request->query->get('page') ?: 1,
        );

        return $this->json([
            $phones,
            '_links' => 'Welcome to your new controller!'
        ]);
    }

    /**
     * Show a phone.
     *
     * @Route("/api/phone/{id}", name="phone_show", methods={"GET"})
     */
    public function show(string $id, Request $request, PhoneManager $manager): Response
    {
        $phone = $manager->getPhoneById((int) $id);

        return $this->json($phone);
    }
}
