<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\CustomerManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CustomerController extends AbstractController
{
    /**
     * @Route("/api/customer", name="customer_index", methods={"GET"})
     */
    public function index(Request $request, CustomerManager $manager): Response
    {
        $customers = $manager->getPaginatedCustomers(
            (int) $request->query->get('start') ?: 1,
            (int) $request->query->get('limit') ?: 10
        );

        return $this->json([
            $customers,
            '_links' => 'Welcome to your new controller!'
        ]);
    }

    /**
     * @Route("/api/customer/{id}", name="customer_show", methods={"GET"})
     */
    public function show(string $id, Request $request, CustomerManager $manager): Response
    {
        $customer = $manager->getCustomerByIdAndUser((int) $id);

        return $this->json($customer);
    }
}
